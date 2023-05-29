<?php

class WDSViewWidgetSlideshow {
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

  public function display() {}

  function widget( $args, $instance ) {
    extract($args);
    $title = (isset($instance['title']) ? $instance['title'] : "");
    $id = (isset($instance['id']) ? $instance['id'] : 0);
    // Before widget.
    echo $before_widget;
    // Title of widget.
    if ( $title ) {
      echo $before_title . $title . $after_title;
    }
    // Widget output.
    require_once(WDS()->plugin_dir . '/frontend/controllers/WDSControllerSlider.php');
    $controller_class = 'WDSControllerSlider';
    $controller = new $controller_class();
    $wds = WDW_S_Library::unique_number();
    $params = array( 'id' => $id );
    $controller->execute($id, 1, $wds);
    // After widget.
    echo $after_widget;
  }

  // Widget Control Panel.
  function form($instance, $id_title, $name_title, $id_gallery_id, $name_gallery_id) {
    $defaults = array(
      'title' => __('Slider', WDS()->prefix),
      'id' => 0,
    );
    $instance = wp_parse_args((array) $instance, $defaults);
    $slider_rows = $this->model->get_slider_rows_data();
    ?>
    <p>
      <label for="<?php echo $id_title; ?>"><?php _e('Title:', WDS()->prefix);?></label>
      <input class="widefat" id="<?php echo $id_title; ?>" name="<?php echo $name_title; ?>" type="text" value="<?php echo $instance['title']; ?>" />
    </p>    
    <p>
      <select name="<?php echo $name_gallery_id; ?>" id="<?php echo $id_gallery_id; ?>" class="widefat">
        <option value="0"><?php _e('Select Slider', WDS()->prefix);?></option>
        <?php
        foreach ($slider_rows as $slider_row) {
          ?>
        <option value="<?php echo $slider_row->id; ?>" <?php echo (($instance['id'] == $slider_row->id) ? 'selected="selected"' : ''); ?>><?php echo $slider_row->name; ?></option>
          <?php
        }
        ?>
      </select>
    </p>
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