<?php
/**
 * MadKoffee WC data page.
 */

?>

<div class="wc-madkoffee-page-container">
	<div class="loader">
		<span class="spinner"></span>
	</div>

	<div class="single-metabox">
		<h2><?php esc_html_e( 'Reports', 'madkoffee-customizations' ); ?></h2>

		<?php madkoffee_get_template_part( 'mk-page/order-sources-report' ); ?>
	</div>

	<div class="single-metabox">
		<?php madkoffee_get_template_part( 'mk-page/sales-by-shopmanager' ); ?>
	</div>
</div>
