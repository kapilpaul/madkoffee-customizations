<ul class="order-report-list">
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