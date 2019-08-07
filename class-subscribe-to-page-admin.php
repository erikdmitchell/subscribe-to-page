<?php
/**
 * Subscribe to page admin class
 *
 * @package subscribe_to_page
 * @since   0.1.0
 */

/**
 * Subscribe_to_Page_Admin class.
 */
class Subscribe_to_Page_Admin {

    /**
     * Construct class.
     *
     * @access public
     * @return void
     */
    public function __construct() {
        $this->includes();
        $this->init_hooks();
    }

    /**
     * Include plugin files.
     *
     * @access public
     * @return void
     */
    public function includes() {
        // include classes.
    }

    /**
     * Init hooks for plugin.
     *
     * @access private
     * @return void
     */
    private function init_hooks() {
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts_styles' ) );
        add_action( 'init', array( $this, 'init' ), 0 );
    }

    /**
     * Init function.
     *
     * @access public
     * @return void
     */
    public function init() {
        // nothing.
    }

    /**
     * Include admin scripts and styles.
     *
     * @access public
     * @return void
     */
    public function admin_scripts_styles() {
        // nothing.
    }

}

new Subscribe_to_Page_Admin();
