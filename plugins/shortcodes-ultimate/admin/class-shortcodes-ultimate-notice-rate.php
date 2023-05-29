<?php

/**
 * The "Rate plugin" notice.
 *
 * @since        5.0.0
 *
 * @package      Shortcodes_Ultimate
 * @subpackage   Shortcodes_Ultimate/admin
 */
final class Shortcodes_Ultimate_Notice_Rate extends Shortcodes_Ultimate_Notice
{
    /**
     * Initialize the class and set its properties.
     *
     * @since  5.0.0
     */
    public function __construct( $notice_id, $template_file )
    {
        parent::__construct( $notice_id, $template_file );
        $this->defer_delay = 3 * DAY_IN_SECONDS;
        $this->first_time_delay = 7 * DAY_IN_SECONDS;
    }
    
    /**
     * Display the notice.
     *
     * @since  5.0.0
     */
    public function display_notice()
    {
        // Make sure this is the Plugins screen
        if ( $this->get_current_screen_id() !== 'plugins' ) {
            return;
        }
        // Check user capability
        if ( !$this->current_user_can_view() ) {
            return;
        }
        // Make sure the notice is not dismissed
        if ( $this->is_dismissed() ) {
            return;
        }
        // Display the notice
        $this->include_template();
    }

}