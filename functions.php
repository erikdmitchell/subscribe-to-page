<?php
/**
 * Subscribe to page functions
 *
 * @package subscribe_to_page
 * @since   0.1.0
 */

/**
 * Get settings.
 *
 * @access public
 * @return array
 */
function stp_settings() {
    return get_option( 'subscribe_to_post_settings' );
}
