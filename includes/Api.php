<?php
/**
 * API Class
 * 
 * @package MadKoffee\Customizations\API
 */

namespace MadKoffee\Customizations;

/**
 * API Class
 */
class API {

    /**
     * Initialize the class.
     * 
     * @since 1.0.0
     * 
     * @return void
     */
    public function __construct() {
        add_action( 'rest_api_init', [ $this, 'register_api' ] );
    }

    /**
     * Register the API.
     * 
     * @since 1.0.0
     *
     * @return void
     */
    public function register_api() {
        
    }
}