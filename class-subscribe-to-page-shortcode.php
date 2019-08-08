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
        add_action( 'wp_ajax_nopriv_subscribe_to_page_subscribe', array( $this, 'ajax_subscribe' ) );
        add_action( 'wp_ajax_subscribe_to_page_subscribe', array( $this, 'ajax_subscribe' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'scripts_styles' ) );

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
            array(
                'text' => 'Get email notifications whenever Dell Boomi updates the sub-processors list.',
            ),
            $atts,
            'subscribe_to_page'
        );

        $html = '';

        $html .= '<div class="subscribe-to-page-button-wrap">';
            $html .= '<button class="stp-subscribe-button">Subscribe to Updates</button>';
        $html .= '</div>';

        $html .= '<div class="subscribe-to-page-form-wrap">';
            $html .= '<div id="subscribe-to-page-form-response"></div>';
            $html .= '<div id="stp-ajax-loader-container"><div class="stp-ajax-loader-spinner"><img src="' . get_site_url( null, '/wp-admin/images/wpspin_light-2x.gif' ) . '" /></div></div>';
            $html .= '<form id="subscribe-to-page-form" class="subscribe-to-page-form" method="post">';
                $html .= '<div class="subscribe-to-page-form-row">';
                    $html .= '<div class="stp-text">' . $atts['text'] . '</div>';
                $html .= '</div>';
                $html .= '<div class="subscribe-to-page-form-row">';
                    $html .= '<input type="email" id="email" name="email" value="" placeholder="Email Address" />';
                $html .= '</div>';
                // $html .= '<div class="subscribe-to-page-form-row">';
                    // $html .= '<input type="checkbox" id="unsubscribe" name="unsubscribe" value="1" /><label for="unsubscribe">Unsubscribe</label>';
                // $html .= '</div>';
                $html .= '<button class="subscribe-to-page-form-submit">Subscribe</button>';
            $html .= '</form>';
        $html .= '</div>';

        return $html;
    }

    /**
     * Scripts and styles.
     *
     * @access public
     * @return void
     */
    public function scripts_styles() {
        wp_register_script( 'subscribe-to-page-shortcode-script', SUBSCRIBE_TO_PAGE_URL . 'js/shortcode.min.js', array( 'jquery' ), SUBSCRIBE_TO_PAGE_VERSION, true );

        wp_localize_script(
            'subscribe-to-page-shortcode-script',
            'stpShortcodeObject',
            array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'nonce' => wp_create_nonce( 'stpsubscribeajax' ),
            )
        );

        wp_register_style( 'subscribe-to-page-shortcode-style', SUBSCRIBE_TO_PAGE_URL . 'css/shortcode.min.css', '', SUBSCRIBE_TO_PAGE_VERSION );

        wp_enqueue_script( 'subscribe-to-page-shortcode-script' );
        wp_enqueue_style( 'subscribe-to-page-shortcode-style' );
    }

    /**
     * AJAX load subscribe.
     *
     * @access public
     * @return void
     */
    public function ajax_subscribe() {
        check_ajax_referer( 'stpsubscribeajax', 'security' );

        $response = array(
            'status' => '',
            'message' => '',
        );
        $email = isset( $_POST['email'] ) ? sanitize_text_field( wp_unslash( $_POST['email'] ) ) : '';
        $unsubscribe = isset( $_POST['unsubscribe'] ) ? sanitize_text_field( wp_unslash( $_POST['unsubscribe'] ) ) : '';
        $add_email = true;
        $email_list = get_option( 'subscribe_to_post_emails', array() );

        // unsubscribe as long as we have an email.
        if ( $unsubscribe && ! empty( $email ) ) {
            $response['status'] = 'success';
            $response['message'] = 'Your email address has been removed';

            $add_email = false;

            foreach ( $email_list as $key => $email_address ) :
                if ( $email == $email_address ) :
                    unset( $email_list[ $key ] );
                endif;
            endforeach;
        }

        // error if not or invalid email.
        if ( empty( $email ) || ! is_email( $email ) ) {
            $response['status'] = 'error';
            $response['message'] = 'Please enter a valid email';

            $add_email = false;
        }

        // add to email option.
        if ( $add_email ) {
            $response['status'] = 'success';
            $response['message'] = 'Your email address has been added';

            // check if dup.
            if ( in_array( $email, $email_list ) ) :
                // do nothing - already in list.
                $response['status'] = 'error';
                $response['message'] = 'Your email address is already on our list';
            else :
                $email_list[] = $email;
            endif;
        }

        update_option( 'subscribe_to_post_emails', $email_list );

        echo json_encode( $response );

        wp_die();
    }

}

new Subscribe_To_Page_Shortcode();
