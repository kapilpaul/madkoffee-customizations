<?php
/**
 * MadKoffee WC data page.
 */

?>

<div class="wc-madkoffee-page-container">
	<div class="single-metabox">
		<h2><?php esc_html_e( 'Reports', 'madkoffee-customizations' ); ?></h2>

		<h4><?php esc_html_e( 'No of Orders from Sources:', 'madkoffee-customizations' ); ?></h4>

		<ul class="orders-count-from-sources">
			<li>
				<span class="heading"><?php esc_html_e( 'Name', 'madkoffee-customizations' ); ?></span>
				<span class="heading"><?php esc_html_e( 'This Month', 'madkoffee-customizations' ); ?></span>
				<span class="heading"><?php esc_html_e( 'LifeTime', 'madkoffee-customizations' ); ?></span>
			</li>

			<?php foreach ( $orders_from_sources as $key => $orders_count ) : ?>
				<li>
					<span><?php echo $orders_count['title']; ?></span>
					<span><?php echo $orders_count['monthly_count']; ?></span>
					<span><?php echo $orders_count['lifetime_count']; ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
