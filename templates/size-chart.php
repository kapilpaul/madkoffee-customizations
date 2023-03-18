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
					<td><?php esc_html_e( 'Width (in)', 'madkoffee-customizations' ); ?></td>
					<td><?php esc_html_e( 'Length (in)', 'madkoffee-customizations' ); ?></td>
				</tr>
			</thead>

			<tbody>
				<?php foreach ( $size_chart as $chart_data ) : ?>
				<tr>
					<td><?php echo $chart_data['size']; ?></td>
					<td><?php echo $chart_data['width']; ?></td>
					<td><?php echo $chart_data['length']; ?></td>
				</tr>
				<?php endforeach; ?>

			</tbody>
		</table>

	</div>
	<?php endforeach; ?>
</div>
