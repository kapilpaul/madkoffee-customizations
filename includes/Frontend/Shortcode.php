<?php
/**
 * Class Shortcode
 * 
 * @package MadKoffee\Customizations\Frontend\Shortcode
 */

namespace MadKoffee\Customizations\Frontend;

/**
 * Class Shortcode
 */
class Shortcode {

    /**
     * Constructor class
     * 
     * @since 1.0.0
     * 
     * @return void
     * /
    public function __construct() {
        add_shortcode( 'madkoffee_customizations', [ $this, 'render_frontend' ] );
    }

    /**
     * Render frontend app
     *
     * @param array $atts
     * @param string $content
     * 
     * @since 1.0.0
     *
     * @return string
     */
    public function render_frontend( $atts, $content = '' ) {
        // wp_enqueue_style( 'frontend' );
        // wp_enqueue_script( 'frontend' );

        $content .= 'Hello World!';

        return $content;
    }
}
