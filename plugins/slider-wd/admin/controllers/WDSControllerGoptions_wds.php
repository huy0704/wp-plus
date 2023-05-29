<?php
class WDSControllerGoptions_wds {

  public function __construct() {
  }

  public function execute() {
    $task = WDW_S_Library::get('task');
    $id = WDW_S_Library::get('current_id', 0);
    $message = WDW_S_Library::get('message');
    echo WDW_S_Library::message_id($message);
    if (method_exists($this, $task)) {
      check_admin_referer('nonce_wd', 'nonce_wd');
      $this->$task($id);
    }
    else {
      $this->display();
    }
  }

  public function display() {
    require_once WDS()->plugin_dir . "/admin/models/WDSModelGoptions_wds.php";
    $model = new WDSModelGoptions_wds();

    require_once WDS()->plugin_dir . "/admin/views/WDSViewGoptions_wds.php";
    $view = new WDSViewGoptions_wds($model);
    $view->display($this->get_sliders());
  }

  public function save_font_family() {
    $wds_global_options = json_decode(get_option("wds_global_options"), true);
    $possib_add_ffamily = WDW_S_Library::esc_sanitize_data($_REQUEST, 'possib_add_ffamily', 'sanitize_text_field');
    $possib_add_ffamily_google = WDW_S_Library::esc_sanitize_data($_REQUEST, 'possib_add_ffamily_google', 'sanitize_text_field');
    
    $wds_global_options['possib_add_ffamily'] = $possib_add_ffamily;
    $wds_global_options['possib_add_ffamily_google'] = $possib_add_ffamily_google;
    $global_options = json_encode($wds_global_options);
    update_option("wds_global_options", $global_options);
    
    $page = WDW_S_Library::get('page');
    WDW_S_Library::spider_redirect(add_query_arg(array( 'page'    => $page,
                                                        'task'    => 'display',
                                                        'message' => 1,
                                                 ), admin_url('admin.php')));
  }

  public function save() {
    $register_scripts = (isset($_REQUEST['register_scripts']) ? (int) $_REQUEST['register_scripts'] : 0);
    $loading_gif = WDW_S_Library::esc_sanitize_data($_REQUEST, 'loading_gif', 'sanitize_text_field', 0);
    $permission = WDW_S_Library::esc_sanitize_data($_REQUEST, 'permission', 'sanitize_text_field', 'manage_options');
    $default_layer_fweight = WDW_S_Library::esc_sanitize_data($_REQUEST, 'default_layer_fweight', 'sanitize_text_field');
    $default_layer_start = WDW_S_Library::esc_sanitize_data($_REQUEST, 'default_layer_start', 'sanitize_text_field', 0);
    $default_layer_effect_in = WDW_S_Library::esc_sanitize_data($_REQUEST, 'default_layer_effect_in', 'sanitize_text_field');
    $default_layer_duration_eff_in = WDW_S_Library::esc_sanitize_data($_REQUEST, 'default_layer_duration_eff_in', 'sanitize_text_field', 0);
    $default_layer_infinite_in = WDW_S_Library::esc_sanitize_data($_REQUEST, 'default_layer_infinite_in', 'sanitize_text_field', 1);
    $default_layer_end = WDW_S_Library::esc_sanitize_data($_REQUEST, 'default_layer_end', 'sanitize_text_field', 0);
    $default_layer_effect_out = WDW_S_Library::esc_sanitize_data($_REQUEST, 'default_layer_effect_out', 'sanitize_text_field');
    $default_layer_duration_eff_out = WDW_S_Library::esc_sanitize_data($_REQUEST, 'default_layer_duration_eff_out', 'sanitize_text_field', 0);
    $default_layer_infinite_out = WDW_S_Library::esc_sanitize_data($_REQUEST, 'default_layer_infinite_out', 'sanitize_text_field', 1);
    $default_layer_add_class = WDW_S_Library::esc_sanitize_data($_REQUEST, 'default_layer_add_class', 'sanitize_text_field');
    $default_layer_ffamily = WDW_S_Library::esc_sanitize_data($_REQUEST, 'default_layer_ffamily', 'sanitize_text_field');
    $default_layer_google_fonts = WDW_S_Library::esc_sanitize_data($_REQUEST, 'default_layer_google_fonts', 'sanitize_text_field', 0);
    $spider_uploader = WDW_S_Library::esc_sanitize_data($_REQUEST, 'spider_uploader', 'sanitize_text_field', 0);
    $possib_add_ffamily = WDW_S_Library::esc_sanitize_data($_REQUEST, 'possib_add_ffamily', 'sanitize_text_field');
    $possib_add_ffamily_google = WDW_S_Library::esc_sanitize_data($_REQUEST, 'possib_add_ffamily_google', 'sanitize_text_field');
    $global_options = array(
      'default_layer_fweight'          => $default_layer_fweight,
      'default_layer_start'            => $default_layer_start,
      'default_layer_effect_in'        => $default_layer_effect_in,
      'default_layer_duration_eff_in'  => $default_layer_duration_eff_in,
      'default_layer_infinite_in'      => $default_layer_infinite_in,
      'default_layer_end'              => $default_layer_end,
      'default_layer_effect_out'       => $default_layer_effect_out,
      'default_layer_duration_eff_out' => $default_layer_duration_eff_out,
      'default_layer_infinite_out'     => $default_layer_infinite_out,
      'default_layer_add_class'        => $default_layer_add_class,
      'default_layer_ffamily'          => $default_layer_ffamily,
      'default_layer_google_fonts'     => $default_layer_google_fonts,
      'register_scripts'               => $register_scripts,
      'loading_gif'                    => $loading_gif,
      'permission'                     => $permission,
      'spider_uploader'                => $spider_uploader,
      'possib_add_ffamily'             => $possib_add_ffamily,
      'possib_add_ffamily_google'      => $possib_add_ffamily_google,
    );
    $global_options = json_encode($global_options);
    update_option("wds_global_options", $global_options);
    $page = WDW_S_Library::get('page');
    WDW_S_Library::spider_redirect(add_query_arg(array( 'page'    => $page,
                                                        'task'    => 'display',
                                                        'message' => 1,
                                                 ), admin_url('admin.php')));
  }


  public function change_layer_options() {
    $choose_slider_id = WDW_S_Library::esc_sanitize_data($_REQUEST, 'choose_slider', 'sanitize_text_field');
    $default_layer_ffamily_check = WDW_S_Library::esc_sanitize_data($_REQUEST, 'default_layer_ffamily_check', 'sanitize_text_field', 0);
    $default_layer_fweight_check = WDW_S_Library::esc_sanitize_data($_REQUEST, 'default_layer_fweight_check', 'sanitize_text_field', 0);
    $default_layer_effect_in_check = WDW_S_Library::esc_sanitize_data($_REQUEST, 'default_layer_effect_in_check', 'sanitize_text_field', 0);
    $default_layer_effect_out_check = WDW_S_Library::esc_sanitize_data($_REQUEST, 'default_layer_effect_out_check', 'sanitize_text_field', 0);
    $default_layer_add_class_check = WDW_S_Library::esc_sanitize_data($_REQUEST, 'default_layer_add_class_check', 'sanitize_text_field', 0);

    $default_array = array();
    if ($default_layer_ffamily_check) {
      $default_layer_ffamily = WDW_S_Library::esc_sanitize_data($_REQUEST, 'default_layer_ffamily', 'sanitize_text_field');
      $default_layer_google_fonts = WDW_S_Library::esc_sanitize_data($_REQUEST, 'default_layer_google_fonts', 'sanitize_text_field', 0);
      array_push($default_array, '`ffamily`="' . $default_layer_ffamily . '"', '`google_fonts`="' . $default_layer_google_fonts . '"');
    }
    if ($default_layer_fweight_check) {
      $default_layer_fweight = WDW_S_Library::esc_sanitize_data($_REQUEST, 'default_layer_fweight', 'sanitize_text_field');
      array_push($default_array, '`fweight`="' . $default_layer_fweight . '"');
    }
    if ($default_layer_effect_in_check) {
      $default_layer_start = WDW_S_Library::esc_sanitize_data($_REQUEST, 'default_layer_start', 'sanitize_text_field', 0);
      $default_layer_effect_in = WDW_S_Library::esc_sanitize_data($_REQUEST, 'default_layer_effect_in', 'sanitize_text_field');
      $default_layer_duration_eff_in = WDW_S_Library::esc_sanitize_data($_REQUEST, 'default_layer_duration_eff_in', 'sanitize_text_field', 0);
      $default_layer_infinite_in = WDW_S_Library::esc_sanitize_data($_REQUEST, 'default_layer_infinite_in', 'sanitize_text_field', 1);
      array_push($default_array, '`start`=' . $default_layer_start, '`layer_effect_in`="' . $default_layer_effect_in . '"', '`duration_eff_in`=' . $default_layer_duration_eff_in, '`infinite_in`=' . $default_layer_infinite_in);
    }
    if ($default_layer_effect_out_check) {
      $default_layer_end = WDW_S_Library::esc_sanitize_data($_REQUEST, 'default_layer_end', 'sanitize_text_field', 0);
      $default_layer_effect_out = WDW_S_Library::esc_sanitize_data($_REQUEST, 'default_layer_effect_out', 'sanitize_text_field');
      $default_layer_duration_eff_out = WDW_S_Library::esc_sanitize_data($_REQUEST, 'default_layer_duration_eff_out', 'sanitize_text_field', 0);
      $default_layer_infinite_out = WDW_S_Library::esc_sanitize_data($_REQUEST, 'default_layer_infinite_out', 'sanitize_text_field', 1);
      array_push($default_array, '`end`=' . $default_layer_end, 'layer_effect_out="' . $default_layer_effect_out . '"', 'duration_eff_out=' . $default_layer_duration_eff_out, '`infinite_out`=' . $default_layer_infinite_out);
    }
    if ($default_layer_add_class_check) {
      $default_layer_add_class = WDW_S_Library::esc_sanitize_data($_REQUEST, 'default_layer_add_class', 'sanitize_text_field');
      array_push($default_array, '`add_class`="' . $default_layer_add_class . '"');
    }
    global $wpdb;
    $where = '';
    if ($choose_slider_id != '') {
      $slide_id_arr = $wpdb->get_col($wpdb->prepare("SELECT id FROM " . $wpdb->prefix . "wdsslide WHERE slider_id='%d'", $choose_slider_id));
      $where = ' WHERE slide_id IN ('. implode(',', $slide_id_arr) .')';
    }
    $set = $wpdb->query('UPDATE ' . $wpdb->prefix . 'wdslayer SET ' . implode(',', $default_array) . $where);
    $message = $wpdb->last_error ? 2 : 22;
    $page = WDW_S_Library::get('page');
    WDW_S_Library::spider_redirect(add_query_arg(array(
                                                   'page' => $page,
                                                   'task' => 'display',
                                                   'message' => $message,
                                                 ), admin_url('admin.php')));
  }

  public function get_sliders() {
    global $wpdb;
    $sliders = $wpdb->get_results("SELECT id, name FROM " . $wpdb->prefix . "wdsslider ORDER BY `name` ASC", OBJECT_K);
    if ($sliders) {
      $sliders[0] = new stdclass();
      $sliders[0]->id = '';
      $sliders[0]->name = __('All sliders', WDS()->prefix);
    }
    else {
      $sliders[0] = new stdclass();
      $sliders[0]->id = 0;
      $sliders[0]->name = __('-Select-', WDS()->prefix);
    }

    ksort($sliders);

    return $sliders;
  }
}