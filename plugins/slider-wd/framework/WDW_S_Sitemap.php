<?php


final class WDW_S_Sitemap {
  /**
   * The single instance of the class.
   */
  protected static $_instance = null;

  private $images;

  /**
   * Main WDW_S_Sitemap Instance.
   *
   * Ensures only one instance is loaded or can be loaded.
   *
   * @static
   * @return WDW_S_Sitemap - Main instance.
   */
  public static function instance() {
    if ( is_null( self::$_instance ) ) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  public function add_wpseo_xml_sitemap_images( $images, $post_id ) {
    $this->images = $images;

    $post = get_post($post_id);

    remove_all_shortcodes();
    if ( defined('ELEMENTOR_VERSION') ) {
      \Elementor\Plugin::instance()->frontend->get_builder_content($post->ID);
    }
    add_shortcode('wds', array($this, 'shortcode'));
    do_shortcode($post->post_content);

    return $this->images;
  }

  public function shortcode( $params = array() ) {
    if ( is_array( $params ) ) {
      $id = shortcode_atts(array('id' => WDW_S_Library::get('slider_id', 0)), $params);
      $id = $id['id'];
    }
    else {
      $id = $params;
    }
    if ( 0 < $id ) {
      $images = $this->get_shortcode_images( $id );
      foreach ( $images as $image ) {
        // Include in sitemap self hosted images only.
        if ( strpos($image, site_url()) !== FALSE ) {
          $this->images[] = array(
            'src' => $image,
            'title' => '',
            'alt' => ''
          );
        }
      }
    }
  }

  private function get_shortcode_images( $id ) {
    $images = array();
    // Get slider.
    $slider = WDW_S_Library::get_slider_by_id( $id );
    if ( !empty($slider) ) {
      // Get slider slides.
      $slides = WDW_S_Library::get_slides_by_slider_id( $id, 'asc' );
      if ( !empty($slides) ) {
        foreach ( $slides as $slide ) {
          $slide_ids[] = $slide->id;
          if ( 'image' == $slide->type ) {
            $images[] = $slide->image_url;
          }
        }
        // Get slider slides layers.
        $layers_rows = WDW_S_Library::get_layers_by_slider_id_slide_ids( $id, $slide_ids );
        foreach ( $layers_rows as $slide_layers ) {
          foreach ( $slide_layers as $layer ) {
            if ('image' == $layer->type) {
              $images[] = $layer->image_url;
            }
          }
        }
      }
    }
    return $images;
  }
}