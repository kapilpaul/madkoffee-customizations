<?php
/**
 * Error handler trait
 * 
 * @package MadKoffee\Customizations\Traits\Form_Error
 */

namespace MadKoffee\Customizations\Traits;

/**
 * Error handler trait
 */
trait Form_Error {

    /**
     * Holds the errors
     *
     * @var array
     * 
     * @since 1.0.0
     */
    public $errors = [];

    /**
     * Check if the form has error
     *
     * @param  string  $key
     * 
     * @since 1.0.0
     *
     * @return boolean
     */
    public function has_error( $key ) {
        return isset( $this->errors[ $key ] ) ? true : false;
    }

    /**
     * Get the error by key
     *
     * @param  key $key
     * 
     * @since 1.0.0
     *
     * @return string | false
     */
    public function get_error( $key ) {
        if ( isset( $this->errors[ $key ] ) ) {
            return $this->errors[ $key ];
        }

        return false;
    }
}
