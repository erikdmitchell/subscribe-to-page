<?php
/**
 * Subscribe to page controls.
 *
 * @package subscribe_to_page
 * @since   0.1.0
 */

/**
 * Subscribe_To_Page_Controls class.
 */
class Subscribe_To_Page_Controls {

    /**
     * __construct function.
     *
     * @access public
     * @return void
     */
    public function __construct() {
        add_action( 'admin_enqueue_scripts', array( $this, 'scripts_styles' ) );
        add_action( 'post_submitbox_start', array( $this, 'add_email_button' ) );
        add_action( 'wp_ajax_nopriv_subscribe_to_page_send_email', array( $this, 'send_email' ) );
        add_action( 'wp_ajax_subscribe_to_page_send_email', array( $this, 'send_email' ) );
    }

    /**
     * Scripts and styles.
     *
     * @access public
     * @return void
     */
    public function scripts_styles() {
        wp_register_script( 'subscribe-to-page-controls-script', SUBSCRIBE_TO_PAGE_URL . 'js/page-controls.min.js', array( 'jquery' ), SUBSCRIBE_TO_PAGE_VERSION, true );

        wp_localize_script(
            'subscribe-to-page-controls-script',
            'stpControlsObject',
            array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'nonce' => wp_create_nonce( 'stppagecontrols' ),
            )
        );

        wp_enqueue_script( 'subscribe-to-page-controls-script' );

        wp_enqueue_style( 'subscribe-to-page-controls-style', SUBSCRIBE_TO_PAGE_URL . 'css/page-controls.min.css', '', SUBSCRIBE_TO_PAGE_VERSION );
    }

    /**
     * Add email button to publish box.
     *
     * @access public
     * @param mixed $post array.
     * @return void
     */
    public function add_email_button( $post ) {
        $html = '';

        // our shortcode is in the content, so we can add our button.
        if ( isset( $post->post_content ) && has_shortcode( $post->post_content, 'subscribe_to_page' ) ) :
            $html .= '<div id="update-page-email-action">';
                $html .= '<a href="#">Send update page email</a>';
                $html .= '<input type="hidden" id="stp_post_id" name="stp_post_id" value="' . $post->ID . '" />';
            $html .= '</div>';

            $html .= '<div id="stp-ajax-controls-loader-container"><div class="stp-ajax-controls-loader-spinner"><img src="' . get_site_url( null, '/wp-admin/images/wpspin_light-2x.gif' ) . '" /></div></div>';
        endif;

        echo $html; //phpcs:ignore
    }

    /**
     * Send updated email.
     *
     * @access public
     * @return void
     */
    public function send_email() {
        check_ajax_referer( 'stppagecontrols', 'security' );

        $post_id = isset( $_POST['post_id'] ) ? sanitize_text_field( wp_unslash( $_POST['post_id'] ) ) : '';
        $settings = stp_settings();
        $email_list = get_option( 'subscribe_to_post_emails', array() );
        $subject = 'Boomi ' . get_the_title( $post_id ) . ' page has been updated.';
        $message = $settings['email_notification'];
        $headers = array( 'Content-Type: text/html; charset=UTF-8' );

        $mail_sent = wp_mail( $email_list, $subject, $message, $headers );

        if ( $mail_sent ) :
            $response = 'Email sent.';
        else :
            $response = 'Error: email not sent.';
        endif;

        echo json_encode( $response );

        wp_die();
    }

}

new Subscribe_To_Page_Controls();
