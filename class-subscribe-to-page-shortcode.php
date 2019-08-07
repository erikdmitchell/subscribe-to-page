<?php
/**
 * Subscribe to page shortcode.
 *
 * @package subscribe_to_page
 * @since   0.1.0
 */

/**
 * Boomi_CMS_Shortcodes_Resources class.
 */
class Subscribe_to_Page_Shortcode {

    /**
     * __construct function.
     *
     * @access public
     * @return void
     */
    public function __construct() {
        add_action( 'wp_ajax_nopriv_boomi_load_more_resources', array( $this, 'ajax_load_more_resources' ) );
        add_action( 'wp_ajax_boomi_load_more_resources', array( $this, 'ajax_load_more_resources' ) );

        add_shortcode( 'resources', array( $this, 'boomi_resources_shortcode' ) );
    }

    /**
     * Resources shortcode.
     *
     * @access public
     * @param mixed $atts (array).
     * @return html
     */
    public function boomi_resources_shortcode( $atts ) {
        $atts = shortcode_atts(
            array(
                'category' => 'all',
                'type'  => 'resource, marketo',
                'offset' => 0,
                'orderby' => 'date',
                'category_ignore' => 'boomi, community',
                'show_filters' => true,
            ),
            $atts,
            'news-and-events'
        );
        $atts['count'] = 10;

        $html = '';
        $args = $this->resources_args( $atts );

        wp_enqueue_script( 'boomi-cms-resources-script' );

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

new Subscribe_to_Page_Shortcode();
