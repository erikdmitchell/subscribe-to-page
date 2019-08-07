<?php
/**
 * Subscribe to page class
 *
 * @package subscribe_to_page
 * @since   0.1.0
 */

/**
 * Final Subscribe_To_Page class.
 *
 * @final
 */
final class Subscribe_To_Page {

    /**
     * Version
     *
     * (default value: '1.0.0')
     *
     * @var string
     * @access public
     */
    public $version = '0.1.0';

    /**
     * Settings
     *
     * (default value: '')
     *
     * @var string
     * @access public
     */
    public $settings = '';

    /**
     * _instance
     *
     * (default value: null)
     *
     * @var mixed
     * @access protected
     * @static
     */
    protected static $_instance = null;

    /**
     * Instance function.
     *
     * @access public
     * @static
     * @return instance
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Construct class.
     *
     * @access public
     * @return void
     */
    public function __construct() {
        $this->define_constants();
        $this->includes();
        $this->init_hooks();
    }

    /**
     * Define constants.
     *
     * @access private
     * @return void
     */
    private function define_constants() {
        $this->define( 'SUBSCRIBE_TO_PAGE_VERSION', $this->version );
        $this->define( 'SUBSCRIBE_TO_PAGE_PATH', plugin_dir_path( __FILE__ ) );
        $this->define( 'SUBSCRIBE_TO_PAGE_URL', plugin_dir_url( __FILE__ ) );
        $this->define( 'SUBSCRIBE_TO_PAGE_BASE', plugin_basename( SUBSCRBE_TO_PAGE_PLUGIN_FILE ) );
    }

    /**
     * Custom define function.
     *
     * @access private
     * @param mixed $name string.
     * @param mixed $value string.
     * @return void
     */
    private function define( $name, $value ) {
        if ( ! defined( $name ) ) {
            define( $name, $value );
        }
    }

    /**
     * Include plugin files.
     *
     * @access public
     * @return void
     */
    public function includes() {
        // include classes.
        include_once( SUBSCRIBE_TO_PAGE_PATH . 'class-subscribe-to-page-admin.php' );
        include_once( SUBSCRIBE_TO_PAGE_PATH . 'class-subscribe-to-page-shortcode.php' );
        include_once( SUBSCRIBE_TO_PAGE_PATH . 'functions.php' );
    }

    /**
     * Init hooks for plugin.
     *
     * @access private
     * @return void
     */
    private function init_hooks() {
        add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts_styles' ) );
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
     * Frontend scripts and styles.
     *
     * @access public
     * @return void
     */
    public function frontend_scripts_styles() {
    }

}

/**
 * Subscribe to page function.
 *
 * @access public
 * @return instance
 */
function subscribe_to_page() {
    return Subscribe_To_Page::instance();
}

// Global for backwards compatibility.
$GLOBALS['subscribe_to_page'] = subscribe_to_page();
