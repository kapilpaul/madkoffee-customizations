<?php
/**
 * WooCommerce Customizations for MadKoffee
 *
 * @package MadKoffee\Customizations\WooCommerce
 */

namespace MadKoffee\Customizations;

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
		add_action( 'woocommerce_before_add_to_cart_quantity' , [ $this, 'add_to_cart' ] );
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

	public function add_to_cart() {
		global $post;

		$product = wc_get_product( $post->ID );
		$variations = $product->get_available_variations();

		//23766 23770
//		dump( $variations );
	}
}
