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

	private function hooks() {
		add_filter( 'woocommerce_available_payment_gateways' , [ $this, 'maybe_remove_cod' ], 20);
		add_filter( 'woocommerce_states', [ $this, 'modify_states_with_ecourier' ] );

		add_action( 'wp_enqueue_scripts', [ $this, 'override_places' ], 20 );
	}

	/**
	 * Maybe remove COD.
	 *
	 * @param $gateways
	 *
	 * @return mixed
	 */
	public function maybe_remove_cod( $gateways ) {
		if ( ! is_checkout() ) {
			return $gateways;
		}

		$allowed_places = [
			'dhaka',
			'gazipur',
			'savar',
			'saver',
		];

		if( ! in_array( WC()->customer->get_billing_state(), $allowed_places, true ) ){
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
		if (
			is_plugin_active( 'ship-to-ecourier/ship-to-ecourier.php' ) &&
			function_exists( 'ship_to_ecourier' )
		) {
			$ecourier_cities = ship_to_ecourier()->ecourier->get_city_list();

			if ( is_wp_error( $ecourier_cities ) ) {
				return $states;
			}

			$cities = [];

			foreach ( $ecourier_cities as $city ) {
				$cities[ strtolower( $city['value'] ) ] = $city['name'];
			}

			$states['BD'] = $cities;
		}

		return $states;
	}

	/**
	 * Override places.
	 *
	 * @return void
	 */
	public function override_places() {
		if (
			is_plugin_active( 'ship-to-ecourier/ship-to-ecourier.php' ) &&
			function_exists( 'ship_to_ecourier' )
		) {
			wp_localize_script( 'wc-city-select', 'wc_city_select_params', array(
				'cities' => wp_json_encode( madkoffee_customizations()->BD->get_places() ),
				'i18n_select_city_text' => esc_attr__( 'Select an option&hellip;', 'woocommerce' )
			) );
		}
	}
}
