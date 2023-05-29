<?php

class WDSViewWDSShortcode {
  private $model;

  public function __construct($model) {
    $this->model = $model;
  }

  public function display() {
    $rows = $this->model->get_row_data();
    wp_print_scripts('jquery');
    ?>
    <link rel="stylesheet" href="<?php echo site_url(); ?>/wp-includes/js/tinymce/<?php echo ((get_bloginfo('version') < '3.8') ? 'themes/advanced/skins/wp_theme' : 'plugins/compat3x/css'); ?>/dialog.css" />
    <div class="tabs" role="tablist" tabindex="-1">
      <ul>
        <li id="display_tab" class="current" role="tab" tabindex="0">
          <span>
            <a href="javascript:mcTabs.displayTab('display_tab','display_panel');" onMouseDown="return false;" tabindex="-1">Slider by 10Web</a>
          </span>
        </li>
      </ul>
    </div>
    <div class="panel_wrapper">
      <div id="display_panel" class="panel current" style="height: 90px !important;">
        <table>
          <tr>
            <td style="vertical-align: middle; text-align: left;"><?php _e('Select a Slider', WDS()->prefix);?></td>
            <td style="vertical-align: middle; text-align: left;">
              <select name="wds_id" id="wds_id" style="width: 230px; text-align: left;">
                <option value="0" selected="selected"><?php _e('- Select a Slider -', WDS()->prefix);?></option>
                <?php
                foreach ($rows as $row) {
                  ?>
                <option value="<?php echo $row->id; ?>"><?php echo $row->name; ?></option>
                  <?php
                }
                ?>
              </select>
            </td>
          </tr>
        </table>
      </div>
    </div>
    <div class="mceActionPanel">
      <div style="float: left;">
        <input type="button" id="cancel" name="cancel" value="<?php _e('Cancel', WDS()->prefix);?>" onClick="window.parent.tb_remove();" />
      </div>
      <div style="float: right;">
        <input type="submit" id="insert" name="insert" value="<?php _e('Insert', WDS()->prefix);?>" onClick="wds_insert_shortcode();" />
      </div>
    </div>
    <script type="text/javascript">
      var short_code = get_params("wds");
      if (short_code['id']) {
        document.getElementById("wds_id").value = short_code['id'];
      }

      // Get shortcodes attributes.
      function get_params(module_name) {
        var selected_text = top.tinyMCE.activeEditor.selection.getContent();
        var module_start_index = selected_text.indexOf("[" + module_name);
        var module_end_index = selected_text.indexOf("]", module_start_index);
        var module_str = "";
        if ((module_start_index == 0) && (module_end_index > 0)) {
          module_str = selected_text.substring(module_start_index + 1, module_end_index);
        }
        else {
          return false;
        }
        var params_str = module_str.substring(module_str.indexOf(" ") + 1);
        var key_values = params_str.split(" ");
        var short_code_attr = new Array();
        for (var key in key_values) {
          var short_code_index = key_values[key].split('=')[0];
          var short_code_value = key_values[key].split('=')[1];
          short_code_value = short_code_value.substring(1, short_code_value.length - 1);
          short_code_attr[short_code_index] = short_code_value;
        }
        return short_code_attr;
      }

      function wds_insert_shortcode() {
        if (document.getElementById("wds_id").value && document.getElementById("wds_id").value != 0) {
          window.parent.send_to_editor('[wds id="' + document.getElementById('wds_id').value + '"]');
        }
        window.parent.tb_remove();
      }
    </script>
    <?php
    die();
  }
}
