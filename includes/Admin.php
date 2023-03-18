<?php
/**
 * The admin class
 *
 * @package MadKoffee\Customizations\Admin
 */

namespace MadKoffee\Customizations;

/**
 * The admin class
 */
class Admin {

    /**
     * Initialize the class.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function __construct() {
        $this->dispatch_actions();
        new Admin\Menu();
        new Admin\ACF();
    }

    /**
     * Dispatch and bind actions.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function dispatch_actions() {

    }

}
