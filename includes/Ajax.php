<?php
/**
 * This will contain all ajax request functions.
 */

namespace MadKoffee\Customizations;

/**
 * Class Ajax
 *
 * @package MadKoffee\Customizations
 */
class Ajax {

	/**
	 * Ajax constructor.
	 */
	public function __construct() {
		add_action( 'wp_ajax_mk_get_area_by_district', array( $this, 'mk_get_area_by_district' ) );
	}

	/**
	 * Get areas based on district
	 *
	 * @return void
	 */
	public function mk_get_area_by_district() {
		if ( ! isset( $_POST['_nonce'] ) || empty( $_POST['district'] ) ) {
			wp_send_json_error( __( 'Something went wrong here!', 'madkoffee-customizations' ) );
			wp_die();
		}

		// Block if _nonce field is not available and valid.
		check_ajax_referer( 'madkoffee-admin-nonce', '_nonce' );

		$district = sanitize_text_field( $_POST['district'] );

		$default_areas = madkoffee_customizations()->BD->get_places_by_area( $district );

		if ( ! is_ste_plugin_active() ) {
			wp_send_json_success( $default_areas );
			wp_die();
		}

		$areas = ship_to_ecourier()->ecourier->get_area_by_district( $district );

		if ( is_wp_error( $areas ) ) {
			wp_send_json_success( $default_areas );
		}

		wp_send_json_success( $areas );
		wp_die();
	}
}
