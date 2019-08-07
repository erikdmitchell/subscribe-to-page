<?php
/**
 * Plugin Name: Subscribe to Page
 * Plugin URI:
 * Description: Allows visitors to subscribe to a page so they receive an email when the page is updated.
 * Version: 0.1.0
 * Author: Erik Mitchell
 * Author URI:
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: subscribe-to-page
 * Domain Path: /languages
 *
 * @package subscribe_to_page
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

if ( ! defined( 'SUBSCRBE_TO_PAGE_PLUGIN_FILE' ) ) {
    define( 'SUBSCRBE_TO_PAGE_PLUGIN_FILE', __FILE__ );
}

// Include the main Subscribe_to_Page class.
if ( ! class_exists( 'Subscribe_to_Page' ) ) {
    include_once dirname( __FILE__ ) . '/class-subscribe-to-page.php';
}
