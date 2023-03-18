<?php

namespace MadKoffee\Customizations\PostTypes;

class SizeChart {

	/**
	 * Slug of the custom post type
	 *
	 * @var string
	 */
	const SLUG = 'sizechart';

	/**
	 * Get labels
	 *
	 * @return array
	 */
	public static function get_labels() {
		$labels = [
			'name'                  => _x( 'Size Charts', 'Post Type General Name', 'madkoffee-customizations' ),
			'singular_name'         => _x( 'Size Chart', 'Post Type Singular Name', 'madkoffee-customizations' ),
			'menu_name'             => _x( 'Size Charts', 'Admin Menu text', 'madkoffee-customizations' ),
			'name_admin_bar'        => _x( 'Size Chart', 'Add New on Toolbar', 'madkoffee-customizations' ),
			'archives'              => __( 'Size Chart Archives', 'madkoffee-customizations' ),
			'attributes'            => __( 'Size Chart Attributes', 'madkoffee-customizations' ),
			'parent_item_colon'     => __( 'Parent Size Chart:', 'madkoffee-customizations' ),
			'all_items'             => __( 'All Size Charts', 'madkoffee-customizations' ),
			'add_new_item'          => __( 'Add New Size Chart', 'madkoffee-customizations' ),
			'add_new'               => __( 'Add New', 'madkoffee-customizations' ),
			'new_item'              => __( 'New Size Chart', 'madkoffee-customizations' ),
			'edit_item'             => __( 'Edit Size Chart', 'madkoffee-customizations' ),
			'update_item'           => __( 'Update Size Chart', 'madkoffee-customizations' ),
			'search_items'          => __( 'Search Size Chart', 'madkoffee-customizations' ),
		];

		return $labels;
	}

	/**
	 * Get Args.
	 *
	 * @return array
	 */
	public static function get_args() {
		$args = [
			'label'               => __( 'Size Chart', 'madkoffee-customizations' ),
			'description'         => __( '', 'madkoffee-customizations' ),
			'labels'              => self::get_labels(),
			'menu_icon'           => 'dashicons-align-left',
			'supports'            => [ 'title' ],
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 56,
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => true,
			'hierarchical'        => false,
			'exclude_from_search' => false,
			'show_in_rest'        => false,
			'publicly_queryable'  => true,
		];

		return $args;
	}
}