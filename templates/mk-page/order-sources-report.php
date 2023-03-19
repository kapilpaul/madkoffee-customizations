<h4><?php esc_html_e( 'No of Orders from Sources:', 'madkoffee-customizations' ); ?></h4>

<div class="order-sources-report">
	<div class="form">
		<div class="input">
			<label for="order_sources_from_date"><?php esc_html_e( 'From', 'madkoffee-customizations' ); ?></label>
			<input type="date" id="order_sources_from_date" placeholder="From" class="widefat">
		</div>

		<div class="input">
			<label for="order_sources_to_date"><?php esc_html_e( 'To', 'madkoffee-customizations' ); ?></label>
			<input type="date" id="order_sources_to_date" placeholder="From" class="widefat">
		</div>

		<div class="input">
			<button type="submit" class="button button-primary submit"><?php esc_html_e( 'Submit', 'madkoffee-customizations' ); ?></button>
		</div>
	</div>

	<div class="order-sources-report__data"></div>
</div>