<?php
/**
 * Frontend handler class
 * 
 * @package MadKoffee\Customizations\Frontend
 */

namespace MadKoffee\Customizations;

/**
 * Frontend handler class
 */
class Frontend {
  
    /**
     * Frontend constructor.
     * 
     * @since 1.0.0
     * 
     * @return void
     */
    public function __construct() {
        new Frontend\Shortcode();
    }
}