<?php

class WDSViewWDSExport {

  private $model;

  public function __construct($model) {
    $this->model = $model;
  }

  public function display($slider_id) {
    if ($slider_id) {
      $this->export_onee();
    }
    else {
      $this->export_full();
    }
  }

  public function export_full() {
    $data = $this->model->export_full();
    $this->export($data);
  }

  public function export_onee() {
    $data = $this->model->export_one();
    $this->export($data);
  }

  public function export($data) {
    $t = isset($_GET["imagesexport"]) ? esc_html($_GET["imagesexport"]) : FALSE;
    $filename = "sliders_" . date('Ymd His');
    $xml_str = '<?xml version="1.0" encoding="utf-8" ?>';
    $zip = new ZipArchive();
    $zip->open($filename, ZipArchive::CREATE);
    $xml_str .= '<sliders version="' . get_option('wds_version').'">';
    foreach ($data as $key => $value) {
      $xml_str .= '<slider>';
      foreach ($value as $key_slider => $value_slider) {
        if (!is_array($value_slider)) {
          if (strpos($value_slider, site_url()) !== FALSE) {
            if (strpos($value_slider, WDS()->plugin_url . '/images/') !== FALSE) {
              $value_slider = str_replace(site_url(), WDS()->site_url_placeholder, $value_slider);
            }
            else {
              $file_url = html_entity_decode($value_slider, ENT_QUOTES);
              $base_name = basename($file_url);
              $download_file = file_get_contents( str_replace(" ", "%20", $file_url) );
              if ( !empty($download_file) ) {
                $zip->addFromString(WDS()->site_url_buttons_placeholder . $base_name, $download_file);
              }
              if ($key_slider != "built_in_watermark_url") {
                if ($key_slider != "music_url") {
                  // Create thumbnail url to check if it exist.
                  $thumb_url = str_replace($base_name, 'thumb/' . $base_name, $file_url);
                  // Thumbnail filname from url.
                  $thumb_filename = str_replace(site_url(), ABSPATH, $thumb_url);
                  if (file_exists($thumb_filename)) {
                    // If thumbnail exist (buttons default images).
                    $download_file = file_get_contents(str_replace(" ", "%20", str_replace($base_name, 'thumb/' . $base_name, $file_url)));
                  }
                  if ( !empty($download_file) ) {
                    $zip->addFromString(WDS()->site_url_buttons_placeholder . '_thumb_' . $base_name, $download_file);
                  }
                }
              }
              $value_slider = WDS()->site_url_buttons_placeholder . basename($value_slider);
            }
          }
          $value->built_in_watermark_url = '';
          $xml_str .= '<'. $key_slider.' value="' . htmlspecialchars($value_slider, ENT_QUOTES) . '" />';
        }
        elseif ($key_slider == 'slides') {
          foreach ($value_slider as $key_slides => $value_slides) {
            $xml_str .= '<slide>';
            if (!is_array($value_slides)) {
              $image_url = $value_slides->image_url;
              if ($t == 'true') {
                if ($value_slides->type == 'image') {
                  if (strpos($value_slides->image_url, site_url()) !== FALSE) {
                    $download_file = file_get_contents(html_entity_decode(str_replace(" ", "%20", $image_url), ENT_QUOTES));
                    if ( !empty($download_file) ) {
                      $zip->addFromString(basename(html_entity_decode($image_url, ENT_QUOTES)), $download_file);
                    }
                    $value_slides->image_url = WDS()->site_url_placeholder. basename($image_url);
                  }
                  if (strpos($value_slides->thumb_url , site_url()) !== FALSE) {
                    $download_file = file_get_contents(html_entity_decode(str_replace(" ", "%20", $value_slides->thumb_url), ENT_QUOTES));
                    if ( !empty($download_file) ) {
                      $zip->addFromString('thumb_' . basename(html_entity_decode($image_url, ENT_QUOTES)), $download_file);
                    }
                    $value_slides->thumb_url = WDS()->site_url_placeholder . basename($image_url);
                  }
                }
                if ($value_slides->type == 'video') {
                  if (ctype_digit($value_slides->thumb_url)) {
                    $value_slides->thumb_url = wp_get_attachment_url(get_post_thumbnail_id($value_slides->thumb_url)) ? wp_get_attachment_url(get_post_thumbnail_id($value_slides->thumb_url)) : WDS()->plugin_url . '/images/no-video.png';
                  }
                  if (strpos($value_slides->thumb_url , site_url()) !== FALSE) {
                    $download_file = file_get_contents(html_entity_decode(str_replace(" ", "%20", $value_slides->thumb_url), ENT_QUOTES));
                    if ( !empty($download_file) ) {
                      $zip->addFromString('featured_' . basename(html_entity_decode($value_slides->thumb_url, ENT_QUOTES)), $download_file);
                    }
                    $value_slides->thumb_url = WDS()->site_url_placeholder . basename($value_slides->thumb_url);
                  }
                  if (strpos($value_slides->image_url, site_url()) !== FALSE) {
                    $download_file = file_get_contents(html_entity_decode(str_replace(" ", "%20", $image_url), ENT_QUOTES));
                    if ( !empty($download_file) ) {
                      $zip->addFromString(basename(html_entity_decode($image_url, ENT_QUOTES)), $download_file);
                    }
                    $value_slides->image_url = WDS()->site_url_placeholder. basename($image_url);
                  }
                }
              }
              else {
                if ($value_slides->type == 'image' && strpos($value_slides->image_url, site_url()) !== FALSE) {
                  $value_slides->image_url = '';
                }
                if ($value_slides->type == 'image' && strpos($value_slides->thumb_url, site_url()) !== FALSE) {
                  $value_slides->thumb_url = '';
                }
                if ($value_slides->type == 'video') {
                  $value_slides->image_url = '';
                  $value_slides->thumb_url = '';
                }
              }
            }
            foreach ($value_slides as $key_slide => $value_slide) {
              if ($key_slide == 'slidelayers') {
                foreach ($value_slide as $key_layers => $value_layers) {
                  $xml_str .= '<layer>';
                  if (!is_array($value_layers)) {
                    if ($t == 'true') {
                      if (strpos($value_layers->image_url, site_url()) !== FALSE) {
                        $file_url = html_entity_decode($value_layers->image_url, ENT_QUOTES);
                        $base_name = basename($file_url);
                        $download_file = file_get_contents(str_replace(" ", "%20", $file_url));
                        if ( !empty($download_file) ) {
                          $zip->addFromString($base_name, $download_file);
                        }
                        // Create thumbnail url to check if it exist.
                        $thumb_url = str_replace($base_name, 'thumb/' . $base_name, $file_url);
                        // Thumbnail filname from url.
                        $thumb_filename = str_replace(site_url(), ABSPATH, $thumb_url);
                        if (file_exists($thumb_filename)) {
                          // If thumbnail exist (layer images uploaded with spider uploader).
                          $download_file = file_get_contents(str_replace(" ", "%20", str_replace($base_name, 'thumb/' . $base_name, $file_url)));
                        }
                        if ( !empty($download_file) ) {
                          $zip->addFromString('thumb_' . $base_name, $download_file);
                        }
                        $value_layers->image_url = WDS()->site_url_placeholder . basename($value_layers->image_url);
                      }
                    }
                    elseif (strpos($value_layers->image_url, site_url()) !== FALSE) {
                      $value_layers->image_url = '';
                    }
                  }
                  foreach ($value_layers as $key_layer => $value_layer) {
                    $xml_str .= '<' . $key_layer .'>' . htmlspecialchars($value_layer, ENT_QUOTES) . '</' . $key_layer .'>';
                  }
                  $xml_str .= '</layer>';
                }
              }
              else {
                $xml_str .= '<'.$key_slide .' value="' . htmlspecialchars($value_slide, ENT_QUOTES) . '" />';
              }
            }
            $xml_str .= '</slide>';
          }
        }
      }
      $xml_str .= '</slider>';
    }
    $xml_str .= '</sliders>';
    $zip->addFromString($filename . ".xml", $xml_str);
    $zip->close();
    header("Content-Disposition: attachment; filename=\"$filename.zip\"");
    header("Content-Type:text/xml,  charset=utf-8");
    ob_end_clean();
    readfile($filename);
    @unlink($filename);
    die('');
  }
}