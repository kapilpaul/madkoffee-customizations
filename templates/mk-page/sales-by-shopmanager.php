<?php
/**
 * Sales by shopmanagers.
 */

?>

<h2><?php esc_html_e( 'Sales Report', 'madkoffee-customizations' ); ?></h2>

<div class="sales-report">
	<div class="form">
		<div class="input">
			<label for="sales_from_date"><?php esc_html_e( 'From', 'madkoffee-customizations' ); ?></label>
			<input type="date" id="sales_from_date" placeholder="From" class="widefat">
		</div>

		<div class="input">
			<label for="sales_to_date"><?php esc_html_e( 'To', 'madkoffee-customizations' ); ?></label>
			<input type="date" id="sales_to_date" placeholder="From" class="widefat">
		</div>

		<div class="input">
			<button type="submit" class="button button-primary submit"><?php esc_html_e( 'Submit', 'madkoffee-customizations' ); ?></button>
		</div>
	</div>

	<div class="sales-reports__data"></div>
</div>