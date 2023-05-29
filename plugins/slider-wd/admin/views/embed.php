<?PHP

/**
 * Class WDSViewembed.
 */
class WDSViewembed {
  public function __construct() {
    // Register and include styles and scripts.
    WDS()->register_iframe_scripts();
    wp_print_styles(WDS()->prefix . '_tables');
    wp_print_scripts(WDS()->prefix . '_admin');
  }

  /**
   * Display.
   */
  public function display() {
    echo WDW_S_Library::message_id(0, __('This functionality is disabled in free version.', WDS()->prefix), 'error');

    die();
  }
}
