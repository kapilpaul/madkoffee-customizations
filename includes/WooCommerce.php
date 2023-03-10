<?php
/**
 * WooCommerce Customizations for MadKoffee
 *
 * @package MadKoffee\Customizations\WooCommerce
 */

namespace MadKoffee\Customizations;

use MadKoffee\Customizations\Places\BD;

/**
 * Class WooCommerce
 *
 * @package MadKoffee\Customizations
 */
class WooCommerce {

	/**
	 * WC constructor.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->hooks();
	}

	/**
	 * Hooks.
	 */
	private function hooks() {
		/**
		 * Filters
		 */
		add_filter( 'woocommerce_available_payment_gateways' , [ $this, 'maybe_remove_cod' ], 20);
		add_filter( 'woocommerce_states', [ $this, 'modify_states_with_ecourier' ] );
		add_filter( 'woocommerce_admin_billing_fields', [ $this, 'modify_billing_admin_fields' ], 20 );
		add_filter( 'woocommerce_admin_shipping_fields', [ $this, 'modify_shipping_admin_fields' ], 20 );
		add_filter( 'woocommerce_settings_api_form_fields_cod', [ $this, 'set_up_cod_places' ] );
		add_filter( 'ste_set_shipping_info', [ $this, 'modify_ste_shipping_info' ], 10, 2 );

		$override_inputs_key = [
			'billing_first_name',
			'billing_country',
			'billing_address_1',
			'billing_address_2',
			'billing_state',
			'billing_city',
			'billing_postcode',
			'billing_phone',
		];

		foreach ( $override_inputs_key as $input ) {
			add_filter( 'default_checkout_' . $input , [ $this, 'override_default_value' ] );
		}

		/**
		 * Actions
		 */
		add_action( 'wp_enqueue_scripts', [ $this, 'fe_load_scripts' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'override_places' ], 20 );
		add_action( 'admin_enqueue_scripts', [ $this, 'load_scripts' ] );
	}

	/**
	 * Maybe remove COD.
	 *
	 * @param $gateways
	 *
	 * @return mixed
	 */
	public function maybe_remove_cod( $gateways ) {
		if ( ! is_checkout() || 'dhaka' === WC()->customer->get_billing_state() ) {
			return $gateways;
		}

		if ( is_user_logged_in() ) {
			$user  = wp_get_current_user();
			$roles = ( array ) $user->roles;

			if ( in_array( 'shop_manager', $roles, true ) ) {
				return $gateways;
			}
		}

		$allowed_places = [];

		$payment_gateways = WC()->payment_gateways->payment_gateways();

		if ( isset( $payment_gateways['cod'] ) ) {
			$get_cod_settings = $payment_gateways['cod']->settings;
			$allowed_places   = isset( $get_cod_settings['enable_places_for_cod'] ) ? $get_cod_settings['enable_places_for_cod'] : [];
		}

		$billing_city = strtolower( WC()->customer->get_billing_city() );

		if( ! in_array( $billing_city, $allowed_places, true ) ){
			// then unset the 'cod' key (cod is the unique id of COD Gateway)
			unset( $gateways['cod'] );
		}

		return $gateways;
	}

	/**
	 * Modify woocommerce states with Ecourier data.
	 *
	 * @param array $states States.
	 *
	 * @return array
	 */
	public function modify_states_with_ecourier( $states ) {
		if ( ! is_ste_plugin_active() ) {
			return $states;
		}

		$ecourier_cities = ship_to_ecourier()->ecourier->get_city_list();

		if ( is_wp_error( $ecourier_cities ) ) {
			$states['BD'] = madkoffee_customizations()->BD->get_cities();
			return $states;
		}

		$cities = [];

		foreach ( $ecourier_cities as $city ) {
			$cities[ strtolower( $city['value'] ) ] = $city['name'];
		}

		$states['BD'] = $cities;

		return $states;
	}

	/**
	 * Override places.
	 *
	 * @return void
	 */
	public function override_places() {
		if ( ! is_ste_plugin_active() ) {
			return;
		}

		wp_localize_script( 'wc-city-select', 'wc_city_select_params', array(
			'cities' => wp_json_encode( madkoffee_customizations()->BD->get_places() ),
			'i18n_select_city_text' => esc_attr__( 'Select an option&hellip;', 'woocommerce' )
		) );
	}

	/**
	 * add field for setup cod places.
	 *
	 * @param array $fields COD form fields.
	 *
	 * @return mixed
	 */
	public function set_up_cod_places( $fields ) {
		$fields['enable_places_for_cod'] = array(
			'title'             => __( 'Enable Places for COD', 'woocommerce' ),
			'type'              => 'multiselect',
			'class'             => 'wc-enhanced-select',
			'css'               => 'width: 100%;',
			'default'           => '',
			'description'       => __( 'COD is only available for certain places, set it up here. Dhaka city will be added by default with every selection.', 'madkoffee-customizations' ),
			'options'           => madkoffee_customizations()->BD->get_places_by_country(),
			'desc_tip'          => false,
			'custom_attributes' => array(
				'data-placeholder' => __( 'Select shipping methods', 'woocommerce' ),
			),
		);

		return $fields;
	}

	/**
	 * Modify ship to ecourier payment method.
	 *
	 * @param array $shipping_info Shipping info.
	 * @param \WC_Order $order Order data.
	 *
	 * @return array
	 */
	public function modify_ste_shipping_info( $shipping_info, $order ) {
		$shipping_info['payment_method'] = 'zitengine_bkash' === $order->get_payment_method() ? 'mpay' : 'cod';
		return $shipping_info;
	}

	/**
	 * Modify billing admin fields.
	 *
	 * @param $fields
	 *
	 * @return array
	 */
	public function modify_billing_admin_fields( $fields ) {
		return $this->modify_admin_fields( $fields, 'billing' );
	}

	/**
	 * Modify shipping admin fields.
	 *
	 * @param $fields
	 *
	 * @return array
	 */
	public function modify_shipping_admin_fields( $fields ) {
		return $this->modify_admin_fields( $fields, 'shipping' );
	}

	/**
	 * Modify admin billing and shipping fields.
	 *
	 * @param $fields
	 * @param $type
	 *
	 * @return array
	 */
	public function modify_admin_fields( $fields, $type ) {
		global $theorder, $post;

		if ( ! $theorder instanceof \WC_Order ) {
			return $fields;
		}

		$order = wc_get_order( $post->ID );

		$get_state = "get_{$type}_state";
		$get_city  = "get_{$type}_city";

		$fields['city'] = [
			'label'         => __( 'City', 'woocommerce' ),
			'wrapper_class' => 'form-field-wide',
			'class'         => 'wc-enhanced-select select short form-field-wide',
			'options'       => madkoffee_customizations()->BD->get_places_by_area( strtolower( $order->$get_state() ) ),
			'type'          => 'select',
			'show'          => false,
			'value'         => strtolower( $order->$get_city() ),
		];

		$temp_fields = $fields;

		unset( $fields['country'] );
		unset( $fields['state'] );
		unset( $fields['city'] );

		$temp_fields['state']     = array(
			'label'   => __( 'State / County', 'woocommerce' ),
			'class'   => 'wc-enhanced-select select short',
			'show'    => false,
			'type'    => 'select',
			'options' => madkoffee_customizations()->BD->get_cities(),
			'value'   => strtolower( $order->{$get_state}() ),
		);

		$fields = $this->rearrange_array( 'address_1', $fields, [
			'country' => $temp_fields['country'],
			'state' => $temp_fields['state'],
			'city' => $temp_fields['city'],
		] );

		return $fields;
	}

	/**
	 * Set array in specific position.
	 *
	 * @param $key
	 * @param $data_array
	 * @param $replace_data
	 *
	 * @return array
	 */
	public function rearrange_array( $key, $data_array, $replace_data ) {
		$i = array_search( $key, array_keys( $data_array ) );

		$result = array_slice( $data_array, 0, $i ) + $replace_data + array_slice( $data_array, $i );

		return $result;

	}

	/**
	 * load scripts.
	 */
	public function load_scripts() {
		wp_register_style( 'madkoffee-customizations', MADKOFFEE_ASSETS . '/css/admin.css', [], filemtime( MADKOFFEE_ASSETS_PATH . '/css/admin.css' ) );
		wp_enqueue_style( 'madkoffee-customizations' );

		wp_register_script( 'madkoffee-customizations', MADKOFFEE_ASSETS . '/js/admin.js', [ 'jquery' ], filemtime( MADKOFFEE_ASSETS_PATH . '/js/admin.js' ), true );
		wp_enqueue_script( 'madkoffee-customizations' );

		wp_localize_script(
			'madkoffee-customizations',
			'MADKOFFEE_ADMIN',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'madkoffee-admin-nonce' ),
			)
		);
	}

	public function fe_load_scripts() {
		wp_register_style( 'fe-madkoffee-customizations', MADKOFFEE_ASSETS . '/css/mk-customizations.css', [], filemtime( MADKOFFEE_ASSETS_PATH . '/css/mk-customizations.css' ) );
		wp_enqueue_style( 'fe-madkoffee-customizations' );
	}

	/**
	 * Change input value for the shop manager.
	 *
	 * @param string $value Value of the input.
	 *
	 * @return string
	 */
	public function override_default_value( $value ) {
		if ( ! is_checkout() || ! is_user_logged_in() ) {
			return $value;
		}

		if ( is_user_logged_in() ) {
			$user  = wp_get_current_user();
			$roles = ( array ) $user->roles;

			if ( in_array( 'shop_manager', $roles, true ) ) {
				return null;
			}
		}

		return $value;
	}

	/**
	 * Modify tracking number with Ecourier.
	 *
	 * @param array     $find_replace  Find and replace data.
	 * @param string    $template_type Template type.
	 * @param \WC_Order $order         Order object.
	 *
	 * @return mixed
	 */
	public static function update_pklist_fields( $find_replace, $template_type, $order ) {
		$find_replace['[wfte_paid_seal_extra_text]'] = 'zitengine_bkash' === $order->get_payment_method() ? 'PAID' : '';;

		if ( ! is_ste_plugin_active() ) {
			return $find_replace;
		}

		$order_shipped = ste_get_order_shipping_info( $order->get_order_number() );

		if ( ! $order_shipped ) {
			return $find_replace;
		}

		$find_replace['[wfte_tracking_number]'] = $order_shipped->tracking_id;

		return $find_replace;
	}
}
