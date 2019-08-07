<?php
/**
 * Subscribe to page shortcode.
 *
 * @package subscribe_to_page
 * @since   0.1.0
 */

/**
 * Subscribe_To_Page_Shortcode class.
 */
class Subscribe_To_Page_Shortcode {

    /**
     * __construct function.
     *
     * @access public
     * @return void
     */
    public function __construct() {
        add_action( 'wp_ajax_nopriv_boomi_load_more_resources', array( $this, 'ajax_load_more_resources' ) );
        add_action( 'wp_ajax_boomi_load_more_resources', array( $this, 'ajax_load_more_resources' ) );

        add_shortcode( 'subscribe_to_page', array( $this, 'subscribe_to_page_shortcode' ) );
    }

    /**
     * Subscribe to page shortcode.
     *
     * @access public
     * @param mixed $atts (array).
     * @return html
     */
    public function subscribe_to_page_shortcode( $atts ) {
        $atts = shortcode_atts(
            array(),
            $atts,
            'subscribe_to_page'
        );

        $html = '';

        //wp_enqueue_script( 'boomi-cms-resources-script' );

        return $html;
    }

    /**
     * AJAX load more resources.
     *
     * @access public
     * @return void
     */
    public function ajax_load_more_resources() {
        check_ajax_referer( 'boomiresources', 'security' );

        wp_die();
    }

}

new Subscribe_To_Page_Shortcode();
