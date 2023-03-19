<?php
/**
 * generate sales report
 */

?>

<ul class="order-report-list">
	<li>
		<span class="heading"><?php esc_html_e( 'Name', 'madkoffee-customizations' ); ?></span>
		<span class="heading"><?php esc_html_e( 'No. of Orders', 'madkoffee-customizations' ); ?></span>
		<span class="heading"><?php esc_html_e( 'Total Amount', 'madkoffee-customizations' ); ?></span>
	</li>

	<?php foreach ( $sales_report_data as $sales_report ) : ?>
	<li>
		<span><?php echo $sales_report[ 'display_name' ]; ?></span>
		<span><?php echo $sales_report[ 'order_count' ]; ?></span>
		<span><?php echo $sales_report[ 'total_amount' ]; ?></span>
	</li>
	<?php endforeach; ?>
</ul>
