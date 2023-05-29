<?php

class WDSViewUninstall_wds {
  ////////////////////////////////////////////////////////////////////////////////////////
  // Events                                                                             //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constants                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Variables                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  private $model;


  ////////////////////////////////////////////////////////////////////////////////////////
  // Constructor & Destructor                                                           //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function __construct($model) {
    $this->model = $model;
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Public Methods                                                                     //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function display() {
    global $wpdb;
    $prefix = $wpdb->prefix;
    ?>
    <form class="wds_form" method="post" action="admin.php?page=uninstall_wds" style="width:99%;">
      <?php wp_nonce_field('nonce_wd', 'nonce_wd'); ?>
      <div class="wrap">
        <span class="uninstall_icon"></span>
        <h2><?php _e('Uninstall Slider by 10Web', WDS()->prefix);?></h2>
        <div class="goodbye-text">
          <?php
          $support_team = '<a href="https://10web.io/contact-us/?source=slider-wd" target="_blank">' . __('support team', WDS()->prefix) . '</a>';
          $contact_us = '<a href="https://10web.io/contact-us/?source=slider-wd" target="_blank">' . __('Contact us', WDS()->prefix) . '</a>';
          echo sprintf(__("Before uninstalling the plugin, please Contact our %s. We'll do our best to help you out with your issue. We value each and every user and value what's right for our users in everything we do.<br />
          However, if anyway you have made a decision to uninstall the plugin, please take a minute to %s and tell what you didn't like for our plugins further improvement and development. Thank you !!!", "wds"), $support_team, $contact_us); ?>
        </div>
        <p><?php _e('Deactivating Slider by 10Web plugin does not remove any data that may have been created. To completely remove this plugin, you can uninstall it here.', WDS()->prefix);?>
        </p>
        <p style="color: red;">
          <strong> <?php _e('WARNING:', WDS()->prefix);?></strong>
		  <?php _e('Once uninstalled, this can&rsquo;t be undone. You should use a Database Backup plugin of WordPress to back up all the data first.', WDS()->prefix);?>  
         
        </p>
        <p style="color: red">
          <strong><?php _e('The following Database Tables will be deleted:', WDS()->prefix);?></strong>
        </p>
        <table class="widefat">
          <thead>
            <tr>
              <th><?php _e('Database Tables', WDS()->prefix);?></th>
            </tr>
          </thead>
          <tr>
            <td valign="top">
              <ol>
                  <li><?php echo $prefix; ?>wdsslider</li>
                  <li><?php echo $prefix; ?>wdsslide</li>
                  <li><?php echo $prefix; ?>wdslayer</li>              
              </ol>
            </td>
          </tr>
        </table>
        <p style="text-align: center;"><?php _e('Do you really want to uninstall Slider by 10Web plugin?', WDS()->prefix);?></p>
        <p style="text-align: center;">
          <input type="checkbox" name="<?php _e('Slider by 10Web', WDS()->prefix); ?>" id="check_yes" value="yes" />&nbsp;<label for="check_yes"><?php _e('Yes', WDS()->prefix);?></label>
        </p>
        <p style="text-align: center;">
          <input type="submit" value="<?php _e('UNINSTALL', WDS()->prefix);?>" class="button-primary" onclick="if (check_yes.checked) { 
                                                                                    if (confirm('<?php _e("You are About to Uninstall Slider by 10Web plugin from WordPress.", WDS()->prefix);?>\n<?php _e("This Action Is Not Reversible.", WDS()->prefix);?>')) {
                                                                                        spider_set_input_value('task', 'uninstall');
                                                                                    } else {
                                                                                        return false;
                                                                                    }
                                                                                  }
                                                                                  else {
                                                                                    return false;
                                                                                  }" />
        </p>
      </div>
      <input id="task" name="task" type="hidden" value="" />
    </form>
  <?php
  }

  public function uninstall() {
    $flag = TRUE;
    global $wpdb;
    $this->model->delete_db_tables();
    $prefix = $wpdb->prefix;
    $deactivate_url = add_query_arg(array('action' => 'deactivate', 'plugin' => WDS()->main_file), admin_url('plugins.php'));
    $deactivate_url = wp_nonce_url($deactivate_url, 'deactivate-plugin_' . WDS()->main_file);
    ?>
    <div id="message" class="wd_updated fade">
      <p><?php _e('The following Database Tables successfully deleted:', WDS()->prefix);?></p>
      <p><?php echo $prefix; ?>wdsslider,</p>
      <p><?php echo $prefix; ?>wdsslide,</p>
      <p><?php echo $prefix; ?>wdslayer.</p>
    </div>
    <div class="wrap">
      <h2><?php _e('Uninstall Slider by 10Web', WDS()->prefix);?></h2>
      <p><strong> 
		<?php 
		$deactivate_html  = '<a href="'. $deactivate_url .'" class="wds_deactivate_link" data-uninstall="1">' . __('Click Here', WDS()->prefix) . '</a>';
		echo sprintf(__("%s To Finish the Uninstallation and Slider by 10Web will be Deactivated Automatically.", "wds"), $deactivate_html);
		?>
	   </strong></p>
      <input id="task" name="task" type="hidden" value="" />
    </div>
  <?php
  }
  
  ////////////////////////////////////////////////////////////////////////////////////////
  // Getters & Setters                                                                  //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Private Methods                                                                    //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Listeners                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
}