<?php
/**
 * Size chart template.
 */

?>

<div class="size-chart">
	<?php foreach ( $size_chart_data as $title => $size_chart ) : ?>

	<h4><?php printf(
			'%1$s (%2$s)',
			esc_html__( 'Size Chart', 'madkoffee-customizations' ),
			$title
		); ?></h4>

	<div class="size_chart_table">

		<table>
			<thead>
				<tr>
					<td><?php esc_html_e( 'Size', 'madkoffee-customizations' ); ?></td>

					<?php if ( 'tshirt' === $size_chart['type'] ) : ?>
						<td><?php esc_html_e( 'Chest (in)', 'madkoffee-customizations' ); ?></td>
					<?php else: ?>
						<td><?php esc_html_e( 'Waist (in)', 'madkoffee-customizations' ); ?></td>
					<?php endif; ?>

					<td><?php esc_html_e( 'Length (in)', 'madkoffee-customizations' ); ?></td>

					<?php if ( $size_chart['display_sleeve'] && 'tshirt' === $size_chart['type'] ) : ?>
					<td><?php esc_html_e( 'Sleeve (in)', 'madkoffee-customizations' ); ?></td>
					<?php endif; ?>
				</tr>
			</thead>

			<tbody>
				<?php foreach ( $size_chart['sizes'] as $chart_data ) : ?>
				<tr>
					<td><?php echo $chart_data['size']; ?></td>

					<?php if ( 'tshirt' === $size_chart['type'] ) : ?>
						<td><?php echo $chart_data['width']; ?></td>
					<?php else: ?>
						<td><?php echo $chart_data['waist']; ?></td>
					<?php endif; ?>

					<td><?php echo $chart_data['length']; ?></td>

					<?php if ( $size_chart['display_sleeve'] && 'tshirt' === $size_chart['type'] ) : ?>
					<td><?php echo $chart_data['sleeve']; ?></td>
					<?php endif; ?>
				</tr>
				<?php endforeach; ?>

			</tbody>
		</table>

		<p class="warning">
			** <?php esc_html_e( '+/- 0.5 Inch Standard Error', 'madkoffee-customizations' ); ?>
		</p>

	</div>
	<?php endforeach; ?>
</div>
