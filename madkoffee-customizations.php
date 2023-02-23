<?php
/**
 * Plugin Name: Madkoffee Customizations
 * Plugin URI: https://wp-generator.com
 * Description: Customizations for MadKoffee website
 * Version: 1.0.0
 * Author: Kapil Paul
 * Author URI: https://kapilpaul.me/
 * License: GPLv2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: madkoffee-customizations
 * Domain Path: /languages
 *
 * @package Madkoffee Customizations
 */

/**
 * Copyright (c) 2023 Kapil Paul (email: kapilpaul007@gmail.com). All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * **********************************************************************
 */

// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * MadKoffee_Customizations class
 *
 * @class MadKoffee_Customizations The class that holds the entire MadKoffee_Customizations plugin
 */
final class MadKoffee_Customizations {
    /**
     * Plugin version
     *
     * @var string
     *
     * @since 1.0.0
     */
    const VERSION = '1.0.0';

    /**
     * Holds various class instances.
     *
     * @var array
     *
     * @since 1.0.0
     */
    private $container = [];

    /**
     * Constructor for the MadKoffee_Customizations class.
     *
     * Sets up all the appropriate hooks and actions
     * within our plugin.
     *
     * @since 1.0.0
     */
    private function __construct() {
        $this->define_constants();

        register_activation_hook( __FILE__, [ $this, 'activate' ] );
        register_deactivation_hook( __FILE__, [ $this, 'deactivate' ] );

        add_action( 'plugins_loaded', [ $this, 'init_plugin' ] );
    }

    /**
     * Initializes the MadKoffee_Customizations() class.
     *
     * Checks for an existing MadKoffee_Customizations() instance
     * and if it doesn't find one, creates it.
     *
     * @since 1.0.0
     *
     * @return MadKoffee_Customizations|bool
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new MadKoffee_Customizations();
        }

        return $instance;
    }

    /**
     * Magic getter to bypass referencing plugin.
     *
     * @param $prop
     *
     * @since 1.0.0
     *
     * @return mixed
     */
    public function __get( $prop ) {
        if ( array_key_exists( $prop, $this->container ) ) {
            return $this->container[ $prop ];
        }

        return $this->{$prop};
    }

    /**
     * Magic isset to bypass referencing plugin.
     *
     * @param $prop
     *
     * @since 1.0.0
     *
     * @return mixed
     */
    public function __isset( $prop ) {
        return isset( $this->{$prop} ) || isset( $this->container[ $prop ] );
    }

    /**
     * Define the constants.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function define_constants() {
        define( 'MADKOFFEE_VERSION', self::VERSION );
        define( 'MADKOFFEE_FILE', __FILE__ );
        define( 'MADKOFFEE_PATH', dirname( MADKOFFEE_FILE ) );
        define( 'MADKOFFEE_INCLUDES', MADKOFFEE_PATH . '/includes' );
        define( 'MADKOFFEE_TEMPLATE_PATH', MADKOFFEE_PATH . '/templates/' );
        define( 'MADKOFFEE_URL', plugins_url( '', MADKOFFEE_FILE ) );
        define( 'MADKOFFEE_ASSETS', MADKOFFEE_URL . '/assets' );
    }

    /**
     * Load the plugin after all plugis are loaded.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function init_plugin() {
        $this->includes();
        $this->init_hooks();
    }

    /**
     * Placeholder for activation function.
     *
     * Nothing being called here yet.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function activate() {
        $installer = new MadKoffee\Customizations\Installer();
        $installer->run();
    }

    /**
     * Placeholder for deactivation function.
     *
     * Nothing being called here yet.
     *
     * @since 1.0.0
     */
    public function deactivate() {

    }

    /**
     * Include the required files.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function includes() {
        if ( $this->is_request( 'admin' ) ) {
            $this->container['admin'] = new MadKoffee\Customizations\Admin();
        }

        if ( $this->is_request( 'frontend' ) ) {
            $this->container['frontend'] = new MadKoffee\Customizations\Frontend();
        }

        if ( $this->is_request( 'ajax' ) ) {
            // require_once MADKOFFEE_INCLUDES . '/class-ajax.php';
        }
    }

    /**
     * Initialize the hooks.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function init_hooks() {
        add_action( 'init', [ $this, 'init_classes' ] );

        // Localize our plugin
        add_action( 'init', [ $this, 'localization_setup' ] );
    }

    /**
     * Instantiate the required classes.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function init_classes() {
        if ( $this->is_request( 'ajax' ) ) {
            // $this->container['ajax'] =  new MadKoffee\Customizations\Ajax();
        }

	    $this->container['api']         = new MadKoffee\Customizations\Api();
	    $this->container['assets']      = new MadKoffee\Customizations\Assets();
	    $this->container['BD']          = new MadKoffee\Customizations\Places\BD();
	    $this->container['woocommerce'] = new MadKoffee\Customizations\WooCommerce();
    }

    /**
     * Initialize plugin for localization.
     *
     * @uses load_plugin_textdomain()
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function localization_setup() {
        load_plugin_textdomain( 'madkoffee-customizations', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

    /**
     * What type of request is this?
     *
     * @param string $type admin, ajax, cron or frontend.
     *
     * @since 1.0.0
     *
     * @return bool
     */
    private function is_request( $type ) {
        switch ( $type ) {
            case 'admin' :
                return is_admin();

            case 'ajax' :
                return defined( 'DOING_AJAX' );

            case 'rest' :
                return defined( 'REST_REQUEST' );

            case 'cron' :
                return defined( 'DOING_CRON' );

            case 'frontend' :
                return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
        }
    }

} // MadKoffee_Customizations

/**
 * Initialize the main plugin.
 *
 * @since 1.0.0
 *
 * @return \MadKoffee_Customizations|bool
 */
function madkoffee_customizations() {
    return MadKoffee_Customizations::init();
}

/**
 * Kick-off the plugin.
 *
 * @since 1.0.0
 */
madkoffee_customizations();
