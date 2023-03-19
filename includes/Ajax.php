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
		add_action( 'wp_ajax_mk_get_sales_report_data', array( $this, 'mk_get_sales_report_data' ) );
		add_action( 'wp_ajax_mk_get_order_sources_report_data', array( $this, 'mk_get_order_sources_report_data' ) );
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

	/**
	 * Get sales report data.
	 *
	 * @return void
	 */
	public function mk_get_sales_report_data() {
		$data = wp_unslash( $_POST );

		if ( ! isset( $data['_nonce'] ) || empty( $data['from_date'] ) || empty( $data['to_date'] ) ) {
			wp_send_json_error( __( 'Something went wrong here!', 'madkoffee-customizations' ) );
			wp_die();
		}

		// Block if _nonce field is not available and valid.
		check_ajax_referer( 'madkoffee-admin-nonce', '_nonce' );

		$from_date = sanitize_text_field( $data['from_date'] );
		$to_date   = sanitize_text_field( $data['to_date'] );

		try {
			$shop_managers = get_users( [
				'role'   => 'shop_manager',
				'fields' => [ 'ID', 'display_name' ],
			] );

			$shop_managers = json_decode( wp_json_encode( $shop_managers ), true );

			foreach ( $shop_managers as $key => $shop_manager ) {
				$orders = wc_get_orders( [
					'meta_key'       => '_customer_user',
					'meta_value'     => $shop_manager['ID'],
					'post_type'      => 'shop_order',
					'post_status'    => 'wc-completed',
					'posts_per_page' => -1,
					'date_query'     => [
						[
							'after'     => [
								'year'  => date( 'Y', strtotime( $from_date ) ),
								'month' => date( 'm', strtotime( $from_date ) ),
								'day'   => date( 'd', strtotime( $from_date ) ),
							],
							'before'    => [
								'year'  => date( 'Y', strtotime( $to_date ) ),
								'month' => date( 'm', strtotime( $to_date ) ),
								'day'   => date( 'd', strtotime( $to_date ) ),
							],
							'inclusive' => true,
						],
					],
				] );

				$shop_managers[ $key ]['order_count'] = count( $orders );

				$total_amount = 0;

				foreach ( $orders as $order ) {
					$total_amount += $order->get_total();
				}

				$shop_managers[ $key ]['total_amount'] = $total_amount;
			}

			ob_start();

			madkoffee_get_template( 'mk-page/generate-sales-report', [
				'sales_report_data' => $shop_managers
			] );

			$sales_report_html = ob_get_clean();

			wp_send_json_success( $sales_report_html );
		} catch ( \Exception $e ) {
			wp_send_json_error( __( 'Something went wrong here!', 'madkoffee-customizations' ) );
			wp_die();
		}
	}

	/**
	 * Get sales report data.
	 *
	 * @return void
	 */
	public function mk_get_order_sources_report_data() {
		$data = wp_unslash( $_POST );

		if ( ! isset( $data['_nonce'] ) || empty( $data['from_date'] ) || empty( $data['to_date'] ) ) {
			wp_send_json_error( __( 'Something went wrong here!', 'madkoffee-customizations' ) );
			wp_die();
		}

		// Block if _nonce field is not available and valid.
		check_ajax_referer( 'madkoffee-admin-nonce', '_nonce' );

		$from_date = sanitize_text_field( $data['from_date'] );
		$to_date   = sanitize_text_field( $data['to_date'] );

		try {
			$orders_from_sources = [];

			foreach ( madkoffee_customizations()->woocommerce->order_sources as $key => $source ) {
				$args = [
					'meta_key'       => 'order_source',
					'meta_value'     => $key,
					'post_type'      => 'shop_order',
					'post_status'    => 'any',
					'posts_per_page' => -1,
				];

				$orders = wc_get_orders( $args );

				$orders_from_sources[ $key ]['title']          = $source;
				$orders_from_sources[ $key ]['lifetime_count'] = count( $orders );

				$args['date_query'] = [
					[
						'after'     => [
							'year'  => date( 'Y', strtotime( $from_date ) ),
							'month' => date( 'm', strtotime( $from_date ) ),
							'day'   => date( 'd', strtotime( $from_date ) ),
						],
						'before'    => [
							'year'  => date( 'Y', strtotime( $to_date ) ),
							'month' => date( 'm', strtotime( $to_date ) ),
							'day'   => date( 'd', strtotime( $to_date ) ),
						],
						'inclusive' => true,
					],
				];

				$orders = wc_get_orders( $args );

				$orders_from_sources[ $key ]['monthly_count'] = count( $orders );
			}

			ob_start();

			madkoffee_get_template( 'mk-page/generate-order-sources-report.php', [
				'orders_from_sources' => $orders_from_sources,
			] );

			$orders_from_sources_report_html = ob_get_clean();

			wp_send_json_success( $orders_from_sources_report_html );
		} catch ( \Exception $e ) {
			wp_send_json_error( __( 'Something went wrong here!', 'madkoffee-customizations' ) );
			wp_die();
		}
	}
}
