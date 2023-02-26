<?php
/**
 * All our plugins custom functions.
 *
 * @since 1.0.0
 */

/**
 * Get template part implementation.
 *
 * Looks at the theme directory first.
 *
 * @param string $slug Slug of template.
 * @param string $name Name of template.
 * @param array  $args Arguments to passed.
 *
 * @since 1.0.0
 *
 * @return void
 */
function madkoffee_get_template_part( $slug, $name = '', $args = [] ) {
  $defaults = [ 'pro' => false ];

  $args = wp_parse_args( $args, $defaults );

  if ( $args && is_array( $args ) ) {
    extract( $args ); //phpcs:ignore
  }

  $template = '';

  // Look in yourtheme/madkoffee-customizations/slug-name.php and yourtheme/madkoffee-customizations/slug.php.
  $template = locate_template(
    [
      MADKOFFEE_TEMPLATE_PATH . "{$slug}-{$name}.php",
      MADKOFFEE_TEMPLATE_PATH . "{$slug}.php",
    ]
  );

  /**
  * Change template directory path filter.
  *
  * @since 1.0.0
  */
  $template_path = apply_filters( 'madkoffee_set_template_path', MADKOFFEE_TEMPLATE_PATH, $template, $args );

  // Get default slug-name.php.
  if ( ! $template && $name && file_exists( $template_path . "/{$slug}-{$name}.php" ) ) {
    $template = $template_path . "/{$slug}-{$name}.php";
  }

  if ( ! $template && ! $name && file_exists( $template_path . "/{$slug}.php" ) ) {
    $template = $template_path . "/{$slug}.php";
  }

  // Allow 3rd party plugin filter template file from their plugin.
  $template = apply_filters( 'madkoffee_get_template_part', $template, $slug, $name );

  if ( $template ) {
    include $template;
  }
}

/**
* Get other templates (e.g. product attributes) passing attributes and including the file.
*
* @param mixed  $template_name Template Name.
* @param array  $args          (default: array()) arguments.
* @param string $template_path (default: '').
* @param string $default_path  (default: '').
*
* @since 1.0.0
*
* @return void
*/
function madkoffee_get_template( $template_name, $args = [], $template_path = '', $default_path = '' ) {
  if ( $args && is_array( $args ) ) {
    extract( $args ); //phpcs:ignore
  }

  $extension = madkoffee_get_extension( $template_name ) ? '' : '.php';

  $located = madkoffee_locate_template( $template_name . $extension, $template_path, $default_path );

  if ( ! file_exists( $located ) ) {
    _doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', esc_html( $located ) ), '2.1' );

    return;
  }

  do_action( 'madkoffee_before_template_part', $template_name, $template_path, $located, $args );

  include $located;

  do_action( 'madkoffee_after_template_part', $template_name, $template_path, $located, $args );
}

/**
* Locate a template and return the path for inclusion.
*
* This is the load order:
*
*      yourtheme       /   $template_path  /   $template_name
*      yourtheme       /   $template_name
*      $default_path   /   $template_name
*
* @param mixed  $template_name Template name.
* @param string $template_path (default: '').
* @param string $default_path  (default: '').
*
* @since 1.0.0
*
* @return string
*/
function madkoffee_locate_template( $template_name, $template_path = '', $default_path = '' ) {
  if ( ! $template_path ) {
    $template_path = MADKOFFEE_TEMPLATE_PATH;
  }

  if ( ! $default_path ) {
    $default_path = MADKOFFEE_TEMPLATE_PATH;
  }

  // Look within passed path within the theme - this is priority.
  $template = locate_template(
    [
      trailingslashit( $template_path ) . $template_name,
    ]
  );

  // Get default template.
  if ( ! $template ) {
    $template = $default_path . $template_name;
  }

  // Return what we found.
  return apply_filters( 'madkoffee_locate_template', $template, $template_name, $template_path );
}

/**
* Get filename extension.
*
* @param string $file_name File name.
*
* @since 1.0.0
*
* @return false|string
*/
function madkoffee_get_extension( $file_name ) {
  $n = strrpos( $file_name, '.' );

  return ( false === $n ) ? '' : substr( $file_name, $n + 1 );
}

/**
 * Ship to ecourier plugin active or not.
 *
 * @return bool
 */
function is_ste_plugin_active() {
	return is_plugin_active( 'ship-to-ecourier/ship-to-ecourier.php' ) && function_exists( 'ship_to_ecourier' );
}
