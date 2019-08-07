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
class Subscribe_To_Page_Admin {

    public $notices = array();

    /**
     * Construct class.
     *
     * @access public
     * @return void
     */
    public function __construct() {
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts_styles' ) );
        add_action( 'admin_init', array( $this, 'check_email_updates' ) );
        add_action( 'admin_menu', array( $this, 'init' ), 0 );
        add_action( 'admin_notices', array( $this, 'admin_notices' ) );
    }

    /**
     * Init function.
     *
     * @access public
     * @return void
     */
    public function init() {
        add_options_page( 'Subscribe to Page', 'Subscribe to Page', 'manage_options', 'subscribe-to-page', array( $this, 'admin_page' ) );
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

    public function admin_page() {
        $email_list = get_option( 'subscribe_to_post_emails', array() );
        ?>
        
        <div class="wrap">
            <h1>Subscribe to Page Settings</h1>

            <h2>Settings</h2>
            
            <form id="subscribe-to-page-admin-settings" method="post">
                <?php wp_nonce_field( 'update_settings', 'stp-admin-settings' ); ?>

                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row"><label for="email_notification">Email Notification</label></th>
                            <td>
                                <p>This is the email notification that will go out when the page is updated.</p>
                                <p><textarea name="email_notification" rows="10" cols="50" id="email_notification" class="large-text code"></textarea></p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
            </form>
               
            <h2>Email List</h2>
            
            <div id="subscribe-to-page-admin-email-list">

                <form id="stp-admin-add-email" method="post">
                    <?php wp_nonce_field( 'add_email', 'stp_admin_add_email' ); ?>
                    <div class="tablenav bottom">
                        <div class="alignleft actions bulkactions">
                            <input type="email" name="email" id="add_email" class="add_email" value="">
                            <input type="submit" id="doaction" class="button action" value="Add email">
                        </div>
                        <br class="clear">
                    </div> 
                </form>
                
                <table class="wp-list-table widefat fixed striped stp-email-list">
                    <thead>
                        <tr>
                            <th scope="col" id="name" class="manage-column column-email column-primary">
                                <span>Email</span>
                            </th>
                            
                            <th scope="col" id="actions" class="manage-column column-actions">
                                <span></span>
                            </th>
                        </tr>
                    </thead>

                    <tbody id="the-list">
                        <?php foreach ( $email_list as $key => $email ) : ?>
                            <tr id="email-<?php echo $key; ?>" class="email-<?php echo $key; ?> email hentry">
                                <td class="email column-email column-primary" data-colname="Email">
                                    <div class="row-name"><?php echo $email; ?></div>
                                </td>
                                
                                <td class="actions column-actions" data-colname="Actions">
                                    <strong><a class="delete" href="<?php echo admin_url( 'options-general.php?page=subscribe-to-page&action=remove-email&emailid=' . $key ); ?>" aria-label="delete">Delete</a></strong>
                                </td>               
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>               
            </div>
        </div>
        
        <?php
    }

    public function check_email_updates() {
        if ( isset( $_POST['stp_admin_add_email'] ) && wp_verify_nonce( sanitize_key( $_POST['stp_admin_add_email'] ), 'add_email' ) ) :
            $this->add_email();
        elseif ( isset( $_GET['action'] ) && 'remove-email' == $_GET['action'] ) :
            $this->remove_email();
        endif;
    }

    public function add_email() {
        $email = isset( $_POST['email'] ) ? sanitize_text_field( wp_unslash( $_POST['email'] ) ) : '';
        $add_email = true;
        $email_list = get_option( 'subscribe_to_post_emails', array() );

        // error if not or invalid email.
        if ( empty( $email ) || ! is_email( $email ) ) {
            $this->notices['error'] = 'Invalid email address';

            $add_email = false;
        }

        // add to email option.
        if ( $add_email ) {
            // check if dup.
            if ( in_array( $email, $email_list ) ) :
                $this->notices['error'] = 'Already on the list';
            else :
                $email_list[] = $email;
                $this->notices['success'] = 'Email address added';
            endif;
        }

        update_option( 'subscribe_to_post_emails', $email_list );
    }

    public function remove_email() {
        $email_id = isset( $_GET['emailid'] ) ? sanitize_text_field( wp_unslash( $_GET['emailid'] ) ) : '';
        $email_list = get_option( 'subscribe_to_post_emails', array() );

        // error if not or invalid email.
        if ( empty( $email_id ) ) :
            $this->notices['error'] = 'Invalid email address';
        elseif ( isset( $email_list[ $email_id ] ) ) :
            $this->notices['success'] = 'Email address removed';
            unset( $email_list[ $email_id ] );
        endif;

        update_option( 'subscribe_to_post_emails', $email_list );
    }

    public function admin_notices() {
        if ( empty( $this->notices ) ) {
            return;
        }

        foreach ( $this->notices as $notice_class => $notice_message ) :
            ?>
            <div class="notice notice-<?php echo $notice_class; ?> is-dismissible">
                <p><?php echo $notice_message; ?></p>
            </div>
            <?php
        endforeach;
    }
}

new Subscribe_To_Page_Admin();
