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
        add_action( 'admin_menu', array( $this, 'init' ), 0 );
    }

    /**
     * Init function.
     *
     * @access public
     * @return void
     */
    public function init() {
        add_options_page('Subscribe to Page', 'Subscribe to Page', 'manage_options', 'subscribe-to-page', array($this, 'admin_page'));
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
                <?php wp_nonce_field('update_settings', 'stp-admin-settings'); ?>

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
            
            <form id="subscribe-to-page-admin-email-list" method="post">

                <table class="wp-list-table widefat fixed striped stp-email-list">
                    <thead>
                        <tr>
                            <td id="cb" class="manage-column column-cb check-column">
                                <label class="screen-reader-text" for="cb-select-all-1">Select All</label>
                                <input id="cb-select-all-1" type="checkbox">
                            </td>
                            
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
                                <th scope="row" class="check-column">
                                    <label class="screen-reader-text" for="cb-select-<?php echo $key; ?>">Select Email</label>
                                    <input id="cb-select-<?php echo $key; ?>" type="checkbox" name="stp_emails[]" value="<?php echo $email; ?>">
                                </th>
                                
                                <td class="email column-email column-primary" data-colname="Email">
                                    <div class="row-name"><?php echo $email; ?></div>
                                </td>
                                
                                <td class="actions column-actions" data-colname="Actions">
                                    <strong><a class="delete" href="#" aria-label="delete">Delete</a></strong>
                                </td>               
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="tablenav bottom">
                    <div class="alignleft actions bulkactions">
                        <label for="bulk-action-selector-bottom" class="screen-reader-text">Select bulk action</label>
                        <select name="action" id="bulk-action-selector-bottom">
                            <option value="-1">Bulk Actions</option>
                            <option value="deleteall">Delete</option>
                        </select>
                        
                        <input type="submit" id="doaction" class="button action" value="Apply">
                    </div>
                    <br class="clear">
                </div>
            </form>
        </div>
        
        <?php
    }

}

new Subscribe_To_Page_Admin();
