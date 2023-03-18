<?php
/**
 * Custom posttypes holds.
 *
 * @package MadKoffee\Customizations\PostTypes
 */

namespace MadKoffee\Customizations;


use MadKoffee\Customizations\PostTypes\SizeChart;

class PostTypes {

	public static $post_types;

	/**
	 * PostTypes constructor.
	 */
	public function __construct() {
		$this->set_post_types();

		$this->register_post_types();
	}

	/**
	 * Set post types.
	 *
	 * @return void
	 */
	public function set_post_types() {
		self::$post_types = [
			SizeChart::SLUG => SizeChart::class,
		];
	}

	/**
	 * Register post types.
	 */
	public function register_post_types() {
		foreach ( self::$post_types as $key => $post_type ) {
			register_post_type( $key, $post_type::get_args() );
		}
	}
}