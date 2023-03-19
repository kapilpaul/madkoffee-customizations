<?php
/**
 * ACF configs.
 */

namespace MadKoffee\Customizations\Admin;

/**
 * Class ACF
 *
 * @package MadKoffee\Customizations\Admin
 */
class ACF {
	/**
	 * Construct method.
	 */
	public function __construct() {
		$this->setup_hooks();
	}

	/**
	 * To setup action filter.
	 *
	 * @return void
	 */
	protected function setup_hooks() {

		/**
		 * Filters.
		 */
		if ( madkoffee_is_production() ) {

			add_filter( 'acf/settings/show_admin', '__return_false' );

		}

		add_filter( 'acf/settings/save_json', [ $this, 'acf_json_save_point' ] );

		add_filter( 'acf/settings/load_json', [ $this, 'acf_json_load_point' ] );

	}

	/**
	 * Function to set ACF JSON file path.
	 *
	 * When save a field group, a JSON file will be created (or updated) with the field group and field settings.
	 * Ref: https://www.advancedcustomfields.com/resources/local-json/
	 *
	 * @param string $path Path to ACF JSON files folder.
	 *
	 * @return string Path to ACF JSON files folder.
	 */
	public function acf_json_save_point( $path ) {

		// update path.
		$path = MADKOFFEE_PATH . '/acf-json';

		return $path;
	}

	/**
	 * ACF json load point.
	 *
	 * @param $paths
	 *
	 * @return mixed
	 */
	public function acf_json_load_point( $paths ) {
		// remove original path (optional)
		unset($paths[0]);

		$paths[] = MADKOFFEE_PATH . '/acf-json';

		return $paths;
	}
}