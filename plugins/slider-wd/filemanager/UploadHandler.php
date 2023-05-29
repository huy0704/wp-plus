<?php
/*
 * jQuery File Upload Plugin PHP Class 6.4.1
 * 
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */
 
if (function_exists('current_user_can')) {
  if (!current_user_can(WDS()->options->permission)) {
    die('Access Denied');
  }
}
else {
  die('Access Denied');
}

require_once(WDS()->plugin_dir . '/filemanager/controller.php');
$controller = new FilemanagerController();

$upload_handler = new wds_UploadHandler(array(
  'upload_dir' => $controller->uploads_dir . (isset($_GET['dir']) ? str_replace('\\', '', ($_GET['dir'])) : ''),
  'accept_file_types' => '/\.(gif|jpe?g|png|mp4|flv|webm|aac|m4a|f4a|oga|ogg|mp3|wav|zip)$/i'
));

class wds_UploadHandler {
    protected $options;
    // PHP File Upload error message codes:
    // http://php.net/manual/en/features.file-upload.errors.php
    protected $error_messages = array(
      1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
      2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
      3 => 'The uploaded file was only partially uploaded',
      4 => 'No file was uploaded',
      6 => 'Missing a temporary folder',
      7 => 'Failed to write file to disk',
      8 => 'A PHP extension stopped the file upload',
      'post_max_size' => 'The uploaded file exceeds the post_max_size directive in php.ini',
      'max_file_size' => 'File is too big',
      'min_file_size' => 'File is too small',
      'accept_file_types' => 'Filetype not allowed',
      'max_number_of_files' => 'Maximum number of files exceeded',
      'max_width' => 'Image exceeds maximum width',
      'min_width' => 'Image requires a minimum width',
      'max_height' => 'Image exceeds maximum height',
      'min_height' => 'Image requires a minimum height'
    );

    function __construct($options = null, $initialize = true, $error_messages = null) {
      $this->options = array(
        'script_url' => $this->get_full_url() . '/',
        'upload_dir' => dirname($_SERVER['SCRIPT_FILENAME']) . '/files/',
        'upload_url' => $this->get_full_url() . '/files/',
        'user_dirs' => false,
        'mkdir_mode' => 0755,
        'param_name' => 'files',
        // Set the following option to 'POST', if your server does not support
        // DELETE requests. This is a parameter sent to the client:
        'delete_type' => 'DELETE',
        'access_control_allow_origin' => '*',
        'access_control_allow_credentials' => false,
        'access_control_allow_methods' => array(
          'OPTIONS',
          'HEAD',
          'GET',
          'POST',
          'PUT',
          'PATCH',
          'DELETE'
        ),
        'access_control_allow_headers' => array(
          'Content-Type',
          'Content-Range',
          'Content-Disposition'
        ),
        // Enable to provide file downloads via GET requests to the PHP script:
        'download_via_php' => false,
        // Defines which files can be displayed inline when downloaded:
        'inline_file_types' => '/\.(gif|jpe?g|png)$/i',
        // Defines which files (based on their names) are accepted for upload:
        'accept_file_types' => '/.+$/i',
        // The php.ini settings upload_max_filesize and post_max_size
        // take precedence over the following max_file_size setting:
        'max_file_size' => null,
        'min_file_size' => 1,
        // The maximum number of files for the upload directory:
        'max_number_of_files' => null,
        // Image resolution restrictions:
        'max_width' => ((isset($_REQUEST['file_namesML']) && esc_html($_REQUEST['file_namesML'])) ? (isset($_REQUEST['importer_img_width']) ? (int) $_REQUEST['importer_img_width'] : 1200) : (isset($_POST['upload_img_width']) ? (int) $_POST['upload_img_width'] : 1200)),
        'max_height' => ((isset($_REQUEST['file_namesML']) && esc_html($_REQUEST['file_namesML'])) ? (isset($_REQUEST['importer_img_height']) ? (int) $_REQUEST['importer_img_height'] : 1200) : (isset($_POST['upload_img_height']) ? (int) $_POST['upload_img_height'] : 1200)),	
        'min_width' => 1,
        'min_height' => 1,
        // Set the following option to false to enable resumable uploads:
        'discard_aborted_uploads' => true,
        // Set to true to rotate images based on EXIF meta data, if available:
        'orient_image' => false,
      );
      if (!$this->options['max_width'] || !$this->options['max_height']) {
        $this->options['max_width'] = NULL;
        $this->options['max_height'] = NULL;
      }
      $this->options += array(
        'image_versions' => array(
          '.original' => array(
            'max_width' => $this->options['max_width'],
            'max_height' => $this->options['max_height'],
            'jpeg_quality' => 100
          ),
          'thumb' => array(
            'max_width' => ((isset($_REQUEST['file_namesML']) && esc_html($_REQUEST['file_namesML'])) ? (isset($_REQUEST['importer_thumb_width']) ? (int) $_REQUEST['importer_thumb_width'] : 300) : ((isset($_POST['upload_thumb_width']) && (int) $_POST['upload_thumb_width']) ? (int) $_POST['upload_thumb_width'] : 300)),
            'max_height' => ((isset($_REQUEST['file_namesML']) && esc_html($_REQUEST['file_namesML'])) ? (isset($_REQUEST['importer_thumb_height']) ? (int) $_REQUEST['importer_thumb_height'] : 300) : ((isset($_POST['upload_thumb_height']) && (int) $_POST['upload_thumb_height']) ? (int) $_POST['upload_thumb_height'] : 300)),
            'jpeg_quality' => 90
          ),
        )
      );
      if ($options) {
        $this->options = array_merge($this->options, $options);
      }
      if ($error_messages) {
        $this->error_messages = array_merge($this->error_messages, $error_messages);
      }
      if ($initialize) {
        $this->initialize();
      }
    }

    protected function initialize() {
      switch ($_SERVER['REQUEST_METHOD']) {
        case 'OPTIONS':
        case 'HEAD':
          $this->head();
          break;
        case 'GET':
          $this->get();
          break;
        case 'PATCH':
        case 'PUT':
        case 'POST':
          $this->post();
          break;
        case 'DELETE':
          $this->delete();
          break;
        default:
          $this->header('HTTP/1.1 405 Method Not Allowed');
      }
    }

    protected function get_full_url() {
      $https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
      return
        ($https ? 'https://' : 'http://').
        (!empty($_SERVER['REMOTE_USER']) ? $_SERVER['REMOTE_USER'].'@' : '').
        (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : ($_SERVER['SERVER_NAME'].
        ($https && $_SERVER['SERVER_PORT'] === 443 ||
        $_SERVER['SERVER_PORT'] === 80 ? '' : ':'.$_SERVER['SERVER_PORT']))).
        substr($_SERVER['SCRIPT_NAME'],0, strrpos($_SERVER['SCRIPT_NAME'], '/'));
    }

    protected function get_user_id() {
      @session_start();
      return session_id();
    }

    protected function get_user_path() {
      if ($this->options['user_dirs']) {
        return $this->get_user_id().'/';
      }
      return '';
    }

    protected function get_upload_path($file_name = null, $version = null) {
      $file_name = $file_name ? $file_name : '';
      $version_path = empty($version) ? '' : $version.'/';
      return $this->options['upload_dir'].$this->get_user_path()
            .$version_path.$file_name;
    }

    protected function get_query_separator($url) {
      return strpos($url, '?') === false ? '?' : '&';
    }

    protected function get_download_url($file_name, $version = null) {
      if ($this->options['download_via_php']) {
        $url = $this->options['script_url']
            .$this->get_query_separator($this->options['script_url'])
            .'file='.rawurlencode($file_name);
        if ($version) {
          $url .= '&version='.rawurlencode($version);
        }
        return $url.'&download=1';
      }
      $version_path = empty($version) ? '' : rawurlencode($version).'/';
      return $this->options['upload_url'].$this->get_user_path()
          .$version_path.rawurlencode($file_name);
    }

    protected function set_file_delete_properties($file) {
      $file->delete_url = $this->options['script_url']
          .$this->get_query_separator($this->options['script_url'])
          .'file='.rawurlencode($file->name);
      $file->delete_type = $this->options['delete_type'];
      if ($file->delete_type !== 'DELETE') {
        $file->delete_url .= '&_method=DELETE';
      }
      if ($this->options['access_control_allow_credentials']) {
        $file->delete_with_credentials = true;
      }
    }

    // Fix for overflowing signed 32 bit integers,
    // works for sizes up to 2^32-1 bytes (4 GiB - 1):
    protected function fix_integer_overflow($size) {
      if ($size < 0) {
        $size += 2.0 * (PHP_INT_MAX + 1);
      }
      return $size;
    }

    protected function get_file_size($file_path, $clear_stat_cache = false) {
      /*if ($clear_stat_cache) {
        clearstatcache(true, $file_path);
      }*/
      return $this->fix_integer_overflow(filesize($file_path));

    }

    protected function is_valid_file_object($file_name) {
      $file_path = $this->get_upload_path($file_name);
      if (is_file($file_path) && $file_name[0] !== '.') {
        return true;
      }
      return false;
    }

    protected function get_file_object($file_name) {
      if ($this->is_valid_file_object($file_name)) {
        $file = new stdClass();
        $file->name = $file_name;
        $file->size = $this->get_file_size(
          $this->get_upload_path($file_name)
        );
        $file->url = $this->get_download_url($file->name);
        foreach($this->options['image_versions'] as $version => $options) {
          if (!empty($version)) {
            if (is_file($this->get_upload_path($file_name, $version))) {
              $file->{$version.'_url'} = $this->get_download_url(
                $file->name,
                $version
              );
            }
          }
        }
        $this->set_file_delete_properties($file);
        return $file;
      }
      return null;
    }

    protected function get_file_objects($iteration_method = 'get_file_object') {
      $upload_dir = $this->get_upload_path();
      if (!is_dir($upload_dir)) {
        return array();
      }
      return array_values(array_filter(array_map(
        array($this, $iteration_method),
        scandir($upload_dir)
      )));
    }

    protected function count_file_objects() {
      return count($this->get_file_objects('is_valid_file_object'));
    }

    protected function create_scaled_image($file_name, $version, $options) {
      $file_path = $this->get_upload_path($file_name);
      if (!empty($version) && ($version != 'main')) {
        $version_dir = $this->get_upload_path(null, $version);
        if (!is_dir($version_dir)) {
          mkdir($version_dir, $this->options['mkdir_mode'], true);
        }
        $new_file_path = $version_dir.'/'.$file_name;
      } else {
        $new_file_path = $file_path;
      }
      if (!function_exists('getimagesize')) {
        error_log('Function not found: getimagesize');
        return false;
      }
      list($img_width, $img_height) = @getimagesize(htmlspecialchars_decode($file_path, ENT_COMPAT | ENT_QUOTES));
      if (!$img_width || !$img_height) {
        return false;
      }
      $max_width = $options['max_width'];
      $max_height = $options['max_height'];
      $scale = min(
        $max_width / $img_width,
        $max_height / $img_height
      );
      @ini_set('memory_limit', '-1');
      if (($scale >= 1) || (($max_width == NULL) && ($max_height == NULL))) {
        if ($file_path !== $new_file_path) {
          return copy($file_path, $new_file_path);
        }
        return true;
      }
      if (!function_exists('imagecreatetruecolor')) {
        error_log('Function not found: imagecreatetruecolor');
        return false;
      }
      
      if (empty($options['crop'])) {
        $new_width = $img_width * $scale;
        $new_height = $img_height * $scale;
        $dst_x = 0;
        $dst_y = 0;
        $new_img = @imagecreatetruecolor($new_width, $new_height);
      } else {
        if (($img_width / $img_height) >= ($max_width / $max_height)) {
          $new_width = $img_width / ($img_height / $max_height);
          $new_height = $max_height;
        } else {
          $new_width = $max_width;
          $new_height = $img_height / ($img_width / $max_width);
        }
        $dst_x = 0 - ($new_width - $max_width) / 2;
        $dst_y = 0 - ($new_height - $max_height) / 2;
        $new_img = @imagecreatetruecolor($max_width, $max_height);
      }
      list($width, $height, $type) = getimagesize(htmlspecialchars_decode($file_path, ENT_COMPAT | ENT_QUOTES));
      // switch (strtolower(substr(strrchr($file_name, '.'), 1))) {
      switch ($type) {
        case 2:
          $src_img = @imagecreatefromjpeg($file_path);
          $write_image = 'imagejpeg';
          $image_quality = isset($options['jpeg_quality']) ? $options['jpeg_quality'] : 75;
            break;
        case 1:
          @imagecolortransparent($new_img, @imagecolorallocate($new_img, 0, 0, 0));
          $src_img = @imagecreatefromgif($file_path);
          $write_image = 'imagegif';
          $image_quality = null;
          break;
        case 3:
          @imagecolortransparent($new_img, @imagecolorallocate($new_img, 0, 0, 0));
          @imagealphablending($new_img, false);
          @imagesavealpha($new_img, true);
          $src_img = @imagecreatefrompng($file_path);
          $write_image = 'imagepng';
          $image_quality = isset($options['png_quality']) ? $options['png_quality'] : 9;
          break;
        default:
          $src_img = null;
      }
      $success = $src_img && @imagecopyresampled(
        $new_img,
        $src_img,
        $dst_x,
        $dst_y,
        0,
        0,
        $new_width,
        $new_height,
        $img_width,
        $img_height
      ) && $write_image($new_img, $new_file_path, $image_quality);
      // Free up memory (imagedestroy does not delete files):
      @imagedestroy($src_img);
      @imagedestroy($new_img);
      @ini_restore('memory_limit');
      return $success;
    }

    protected function get_error_message($error) {
      return array_key_exists($error, $this->error_messages) ? $this->error_messages[$error] : $error;
    }

    function get_config_bytes($val) {
      $val = trim($val);
      $last = strtolower($val[strlen($val)-1]);
      switch($last) {
        case 'g':
          $val *= 1024;
        case 'm':
          $val *= 1024;
        case 'k':
          $val *= 1024;
      }
      return $this->fix_integer_overflow($val);
    }

    protected function validate($uploaded_file, $file, $error, $index) {
      if ($error) {
        $file->error = $this->get_error_message($error);
        return false;
      }
      $content_length = $this->fix_integer_overflow(intval($_SERVER['CONTENT_LENGTH']));
      $post_max_size = $this->get_config_bytes(ini_get('post_max_size'));
      if ($post_max_size && ($content_length > $post_max_size)) {
        $file->error = $this->get_error_message('post_max_size');
        return false;
      }
      if (!preg_match($this->options['accept_file_types'], $file->name)) {
        $file->error = $this->get_error_message('accept_file_types');
        return false;
      }
      if ($uploaded_file && is_uploaded_file($uploaded_file)) {
        $file_size = $this->get_file_size($uploaded_file);
      }
      else {
        $file_size = $content_length;
      }
      if ($this->options['max_file_size'] && (
          $file_size > $this->options['max_file_size'] ||
          $file->size > $this->options['max_file_size'])
        ) {
        $file->error = $this->get_error_message('max_file_size');
        return false;
      }
      if ($this->options['min_file_size'] &&
        $file_size < $this->options['min_file_size']) {
        $file->error = $this->get_error_message('min_file_size');
        return false;
      }
      if (is_int($this->options['max_number_of_files']) && (
            $this->count_file_objects() >= $this->options['max_number_of_files'])
          ) {
        $file->error = $this->get_error_message('max_number_of_files');
        return false;
      }
      list($img_width, $img_height) = @getimagesize(htmlspecialchars_decode($uploaded_file, ENT_COMPAT | ENT_QUOTES));
      if (is_int($img_width)) {
        // if ($this->options['max_width'] && $img_width > $this->options['max_width']) {
          // $file->error = $this->get_error_message('max_width');
          // return false;
        // }
        // if ($this->options['max_height'] && $img_height > $this->options['max_height']) {
          // $file->error = $this->get_error_message('max_height');
          // return false;
        // }
        if ($this->options['min_width'] && $img_width < $this->options['min_width']) {
          $file->error = $this->get_error_message('min_width');
          return false;
        }
        if ($this->options['min_height'] && $img_height < $this->options['min_height']) {
          $file->error = $this->get_error_message('min_height');
          return false;
        }
      }
      return true;
    }

    protected function upcount_name_callback($matches) {
      $index = isset($matches[1]) ? intval($matches[1]) + 1 : 1;
      $ext = isset($matches[2]) ? $matches[2] : '';
      return ' ('.$index.')'.$ext;
    }

    protected function upcount_name($name) {
      return preg_replace_callback(
        '/(?:(?: \(([\d]+)\))?(\.[^.]+))?$/',
        array($this, 'upcount_name_callback'),
        $name,
        1
      );
    }

    protected function get_unique_filename($name, $type, $index, $content_range) {
      while(is_dir($this->get_upload_path($name))) {
        $name = $this->upcount_name($name);
      }
      // Keep an existing filename if this is part of a chunked upload:
      $uploaded_bytes = $this->fix_integer_overflow(intval(isset($content_range[1]) ? $content_range[1] : 0));
      while(is_file($this->get_upload_path($name))) {
        if ($uploaded_bytes === $this->get_file_size(
                $this->get_upload_path($name))) {
          break;
        }
        $name = $this->upcount_name($name);
      }
      return $name;
    }

    protected function trim_file_name($name, $type, $index, $content_range) {
      // Remove path information and dots around the filename, to prevent uploading
      // into different directories or replacing hidden system files.
      // Also remove control characters and spaces (\x00..\x20) around the filename:
      $name = trim(stripslashes($name), ".\x00..\x20");
      $name = str_replace(array(" ",'%'), array("_",''), $name);
      // Use a timestamp for empty filenames:
      if (!$name) {
        $name = str_replace('.', '-', microtime(true));
      }
      // Add missing file extension for known image types:
      if (strpos($name, '.') === false &&
        preg_match('/^image\/(gif|jpe?g|png)/', $type, $matches)) {
        $name .= '.'.$matches[1];
      }
      return $name;
    }

    protected function get_file_name($name, $type, $index, $content_range) {
      return $this->get_unique_filename(
        $this->trim_file_name($name, $type, $index, $content_range),
        $type,
        $index,
        $content_range
      );
    }

    protected function handle_form_data($file, $index) {
      // Handle form data, e.g. $_REQUEST['description'][$index]
    }

    protected function orient_image($file_path) {
      if (!function_exists('exif_read_data')) {
        return false;
      }
      $exif = @exif_read_data($file_path);
      if ($exif === false) {
        return false;
      }
      $orientation = intval(@$exif['Orientation']);
      if (!in_array($orientation, array(3, 6, 8))) {
        return false;
      }
      @ini_set('memory_limit', '-1');
      $image = @imagecreatefromjpeg($file_path);
      switch ($orientation) {
        case 3:
          $image = @imagerotate($image, 180, 0);
          break;
        case 6:
          $image = @imagerotate($image, 270, 0);
          break;
        case 8:
          $image = @imagerotate($image, 90, 0);
          break;
        default:
          return false;
      }
      $success = imagejpeg($image, $file_path);
      // Free up memory (imagedestroy does not delete files):
      @imagedestroy($image);
      @ini_restore('memory_limit');
      return $success;
    }

    protected function handle_image_file($file_path, $file) {
      if ($this->options['orient_image']) {
        $this->orient_image($file_path);
      }
      $failed_versions = array();
      foreach($this->options['image_versions'] as $version => $options) {
        if ($this->create_scaled_image($file->name, $version, $options)) {
          if (!empty($version)) {
            $file->{$version.'_url'} = $this->get_download_url(
              $file->name,
              $version
            );
          }
          else {
            $file->size = $this->get_file_size($file_path, true);
          }
        }
        else {
          $failed_versions[] = $version;
        }
      }
      switch (count($failed_versions)) {
        case 0:
          break;
        case 1:
          $file->error = 'Failed to create scaled version: '
              .$failed_versions[0];
          break;
        default:
          $file->error = 'Failed to create scaled versions: '
              .implode(', ', $failed_versions);
      }
    }

    protected function handle_zip_file($file_path, $file) {
      $zip = new ZipArchive;
      $res = $zip->open($file_path);
      if ($res === TRUE) {
        $allow_extract = true;
        for($i = 0; $i < $zip->numFiles; $i++) {
          $OnlyFileName = $zip->getNameIndex($i);
          $FullFileName = $zip->statIndex($i);
          if (!($FullFileName['name'][strlen($FullFileName['name']) - 1] == "/")) {
            if (!preg_match('#\.(gif|jpe?g|png|bmp|mp4|flv|webm|ogg|mp3|wav|pdf|ini|txt)$#i', $OnlyFileName)) {
              $allow_extract = false;
            }
          }
        }
        if ($allow_extract) {
          $target_dir = substr($file_path, 0, strlen($file_path) - 4);
          if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777);
          }
          $zip->extractTo($target_dir);
        }
        else {
          $file->error = 'Zip file should contain only image files.';
        }
        $zip->close();
        if ($allow_extract) {
          $this->handle_directory($target_dir);
        }
      }
      unlink($file_path);
      return $file->error;
    }

    protected function handle_directory($target_dir) {
      $extracted_files = scandir($target_dir);
      if ($extracted_files) {
        $temp_upload_dir = $this->options['upload_dir'];
        $this->options['upload_dir'] = $target_dir . '/';
        foreach ($extracted_files as $ex_file) {
          if ($ex_file != '.' && $ex_file != '..') {
            $ex_file = $target_dir . '/' . $ex_file;
            if (is_file($ex_file)) {
              $type = filetype($ex_file);
              $name = basename($ex_file);
              $index = null;
              $content_range = null;
              $size = $this->get_file_size($ex_file);
              $file = new stdClass();
              $file->name = $name;
              $file->size = $this->fix_integer_overflow(intval($size));
              $file->type = $type;
              $file->url = $this->get_download_url($file->name);
              list($img_width, $img_height) = @getimagesize(htmlspecialchars_decode($ex_file, ENT_COMPAT | ENT_QUOTES));
              if ($this->options['max_width'] && $this->options['max_height']) {
                // Zip Upload.
                $this->create_scaled_image($file->name, 'main', $this->options);
              }
              if (is_int($img_width)) {
                $this->handle_image_file($ex_file, $file);
              }
            }
            elseif (is_dir($ex_file)) {
              $this->handle_directory($ex_file);
            }
          }
        }
        $this->options['upload_dir'] = $temp_upload_dir;
      }
    }

    protected function handle_file_import($uploaded_file, $name, $index = null, $content_range = null) {
      $parent_dir = wp_upload_dir();
      $parent_dir = $parent_dir['basedir'];
      $file_type_array = explode('.', $name);
      $type = strtolower(end($file_type_array));
      
      $file = new stdClass();
      $file->name = $this->get_file_name($name, $type, $index, $content_range);
      $file->type = $type;
      $this->handle_form_data($file, $index);
      $upload_dir = $this->get_upload_path();
      if (!is_dir($upload_dir)) {
        mkdir($upload_dir, $this->options['mkdir_mode'], true);
      }
      $file_path = $this->get_upload_path($file->name);
      
      copy($parent_dir . '/' . $uploaded_file, $file_path);
      list($img_width, $img_height) = @getimagesize(htmlspecialchars_decode($file_path, ENT_COMPAT | ENT_QUOTES));

      if ($this->options['max_width'] && $this->options['max_height']) {
        // Media libruary Upload.
        $this->create_scaled_image($file->name, 'main', $this->options);
      }

      if (is_int($img_width)) {
        $this->handle_image_file($file_path, $file);
      }        
      $this->set_file_delete_properties($file);
      
      return $file;
    }

    protected function handle_file_upload($uploaded_file, $name, $size, $type, $error, $index = null, $content_range = null) {
      $file = new stdClass();
      $file->name = $this->get_file_name($name, $type, $index, $content_range);
      $file->size = $this->fix_integer_overflow(intval($size));
      $file->type = $type;
      if ($this->validate($uploaded_file, $file, $error, $index)) {
        $this->handle_form_data($file, $index);
        $upload_dir = $this->get_upload_path();
        if (!is_dir($upload_dir)) {
          mkdir($upload_dir, $this->options['mkdir_mode'], true);
        }
        $file_path = $this->get_upload_path($file->name);
        $append_file = $content_range && is_file($file_path) &&
            $file->size > $this->get_file_size($file_path);
        if ($uploaded_file && is_uploaded_file($uploaded_file)) {
          // multipart/formdata uploads (POST method uploads)
          if ($append_file) {
            file_put_contents(
              $file_path,
              fopen($uploaded_file, 'r'),
              FILE_APPEND
            );
          }
          else {
            move_uploaded_file($uploaded_file, $file_path);
          }
        }
        else {
          // Non-multipart uploads (PUT method support)
          file_put_contents(
            $file_path,
            fopen('php://input', 'r'),
            $append_file ? FILE_APPEND : 0
          );
        }
        $file_size = $this->get_file_size($file_path, $append_file);
        if ($file_size === $file->size) {
          if ($this->options['max_width'] && $this->options['max_height']) {
            // Upload.
            $this->create_scaled_image($file->name, 'main', $this->options);
          }
          $file->url = $this->get_download_url($file->name);
          list($img_width, $img_height) = @getimagesize(htmlspecialchars_decode($file_path, ENT_COMPAT | ENT_QUOTES));
          if (is_int($img_width)) {
            $this->handle_image_file($file_path, $file);
          }
          else {
            $file->error = $this->handle_zip_file($file_path, $file);
          }
        }
        else {
          $file->size = $file_size;
          if (!$content_range && $this->options['discard_aborted_uploads']) {
            unlink($file_path);
            $file->error = 'abort';
          }
        }
        $this->set_file_delete_properties($file);
      }
      return $file;
    }

    protected function readfile($file_path) {
      return readfile($file_path);
    }

    protected function body($str) {
      echo $str;
    }

    protected function header($str) {
      header($str);
    }

    protected function generate_response($content, $print_response = true) {
      if ($print_response) {
        $json = json_encode($content);
        $redirect = isset($_REQUEST['redirect']) ? stripslashes($_REQUEST['redirect']) : null;
        if ($redirect) {
          $this->header('Location: '.sprintf($redirect, rawurlencode($json)));
          return;
        }
        $this->head();
        if (isset($_SERVER['HTTP_CONTENT_RANGE'])) {
          $files = isset($content[$this->options['param_name']]) ? $content[$this->options['param_name']] : null;
          if ($files && is_array($files) && is_object($files[0]) && $files[0]->size) {
            $this->header('Range: 0-'.($this->fix_integer_overflow(intval($files[0]->size)) - 1));
          }
        }
        $this->body($json);
      }
      return $content;
    }

    protected function get_version_param() {
      return isset($_GET['version']) ? basename(stripslashes($_GET['version'])) : null;
    }

    protected function get_file_name_param() {
      return isset($_GET['file']) ? basename(stripslashes($_GET['file'])) : null;
    }

    protected function get_file_type($file_path) {
      switch (strtolower(pathinfo($file_path, PATHINFO_EXTENSION))) {
        case 'jpeg':
        case 'jpg':
          return 'image/jpeg';
        case 'png':
          return 'image/png';
        case 'gif':
          return 'image/gif';
        default:
          return '';
      }
    }

    protected function download() {
      if (!$this->options['download_via_php']) {
        $this->header('HTTP/1.1 403 Forbidden');
        return;
      }
      $file_name = $this->get_file_name_param();
      if ($this->is_valid_file_object($file_name)) {
        $file_path = $this->get_upload_path($file_name, $this->get_version_param());
        if (is_file($file_path)) {
          if (!preg_match($this->options['inline_file_types'], $file_name)) {
            $this->header('Content-Description: File Transfer');
            $this->header('Content-Type: application/octet-stream');
            $this->header('Content-Disposition: attachment; filename="'.$file_name.'"');
            $this->header('Content-Transfer-Encoding: binary');
          }
          else {
            // Prevent Internet Explorer from MIME-sniffing the content-type:
            $this->header('X-Content-Type-Options: nosniff');
            $this->header('Content-Type: '.$this->get_file_type($file_path));
            $this->header('Content-Disposition: inline; filename="'.$file_name.'"');
          }
          $this->header('Content-Length: '.$this->get_file_size($file_path));
          $this->header('Last-Modified: '.gmdate('D, d M Y H:i:s T', filemtime($file_path)));
          $this->readfile($file_path);
        }
      }
    }

    protected function send_content_type_header() {
      $this->header('Vary: Accept');
      if (isset($_SERVER['HTTP_ACCEPT']) &&
          (strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)) {
        $this->header('Content-type: application/json');
      }
      else {
        $this->header('Content-type: text/plain');
      }
    }

    protected function send_access_control_headers() {
      $this->header('Access-Control-Allow-Origin: '.$this->options['access_control_allow_origin']);
      $this->header('Access-Control-Allow-Credentials: '
          .($this->options['access_control_allow_credentials'] ? 'true' : 'false'));
      $this->header('Access-Control-Allow-Methods: '
          .implode(', ', $this->options['access_control_allow_methods']));
      $this->header('Access-Control-Allow-Headers: '
          .implode(', ', $this->options['access_control_allow_headers']));
    }

    public function head() {
      $this->header('Pragma: no-cache');
      $this->header('Cache-Control: no-store, no-cache, must-revalidate');
      $this->header('Content-Disposition: inline; filename="files.json"');
      // Prevent Internet Explorer from MIME-sniffing the content-type:
      $this->header('X-Content-Type-Options: nosniff');
      if ($this->options['access_control_allow_origin']) {
        $this->send_access_control_headers();
      }
      $this->send_content_type_header();
    }

    public function get($print_response = true) {
      if ($print_response && isset($_GET['download'])) {
        return $this->download();
      }
      $file_name = $this->get_file_name_param();
      if ($file_name) {
        $response = array(
            substr($this->options['param_name'], 0, -1) => $this->get_file_object($file_name)
        );
      }
      else {
        $response = array(
            $this->options['param_name'] => $this->get_file_objects()
        );
      }
      return $this->generate_response($response, $print_response);
    }

    public function post($print_response = true) {
      if (isset($_REQUEST['_method']) && $_REQUEST['_method'] === 'DELETE') {
        return $this->delete($print_response);
      }
      $upload = isset($_FILES[$this->options['param_name']]) ? $_FILES[$this->options['param_name']] : null;
      $files = array();
      // Parse the Content-Disposition header, if available:
      $file_name = isset($_SERVER['HTTP_CONTENT_DISPOSITION']) ? rawurldecode(preg_replace(
          '/(^[^"]+")|("$)/',
          '',
          $_SERVER['HTTP_CONTENT_DISPOSITION']
      )) : null;
      // Parse the Content-Range header, which has the following form:
      // Content-Range: bytes 0-524287/2000000
      $content_range = isset($_SERVER['HTTP_CONTENT_RANGE']) ? preg_split('/[^0-9]+/', $_SERVER['HTTP_CONTENT_RANGE']) : null;
      $size =  $content_range ? $content_range[3] : null;
      if ($upload && is_array($upload['tmp_name'])) {
        // param_name is an array identifier like "files[]",
        // $_FILES is a multi-dimensional array:
        foreach ($upload['tmp_name'] as $index => $value) {
            $files[] = $this->handle_file_upload(
                $upload['tmp_name'][$index],
                $file_name ? $file_name : $upload['name'][$index],
                $size ? $size : $upload['size'][$index],
                $upload['type'][$index],
                $upload['error'][$index],
                $index,
                $content_range
            );
        }
      }
      else {
        // param_name is a single object identifier like "file",
        // $_FILES is a one-dimensional array:
        $files[] = $this->handle_file_upload(
            isset($upload['tmp_name']) ? $upload['tmp_name'] : null,
            $file_name ? $file_name : (isset($upload['name']) ?
                    $upload['name'] : null),
            $size ? $size : (isset($upload['size']) ?
                    $upload['size'] : $_SERVER['CONTENT_LENGTH']),
            isset($upload['type']) ?
                    $upload['type'] : $_SERVER['CONTENT_TYPE'],
            isset($upload['error']) ? $upload['error'] : null,
            null,
            $content_range
        );
      }
      return $this->generate_response(
        array($this->options['param_name'] => $files),
        $print_response
      );
    }

    public function delete($print_response = true) {
      $file_name = $this->get_file_name_param();
      $file_path = $this->get_upload_path($file_name);
      $success = is_file($file_path) && $file_name[0] !== '.' && unlink($file_path);
      if ($success) {
        foreach($this->options['image_versions'] as $version => $options) {
          if (!empty($version)) {
            $file = $this->get_upload_path($file_name, $version);
            if (is_file($file)) {
              unlink($file);
            }
          }
        }
      }
      return $this->generate_response(array('success' => $success), $print_response);
    }

}

die();
