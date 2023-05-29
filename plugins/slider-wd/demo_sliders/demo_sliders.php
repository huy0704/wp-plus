<?php
function import_demo_sliders() {
}
function print_demo_sliders() {
  $error_ext_mess = '';
  if( !function_exists( 'simplexml_load_string' ) ) {
    $error_ext_mess = WDW_S_Library::message_id(0, __('Slider import will not work correctly, as PHP XML Extension is disabled on your website. Please contact your hosting provider and ask them to enable it. ', WDS()->prefix),'error');
  }
  if ( !class_exists('ZipArchive') ) {
    $error_ext_mess .= WDW_S_Library::message_id(0, __('Slider import will not work correctly, as ZipArchive PHP extension is disabled on your website. Please contact your hosting provider and ask them to enable it. ', WDS()->prefix),'error');
  }
  $buy_now_href = 'https://10web.io/plugins/wordpress-slider/?utm_source=slider&utm_medium=free_plugin#plugin_steps';
  $demo_sliders = array(
	'presentation' => array(
					'name' => __('Presentation', WDS()->prefix),
					'eye_href' => 'https://demo.10web.io/slider/?utm_source=slider&utm_medium=free_plugin',
					'download_href' => 'https://demo.10web.io/wp-content/uploads/demo-sliders/presentation.zip',
				),
	'layers' =>  array(
					'name' => __('Layers', WDS()->prefix),
					'eye_href' => 'https://demo.10web.io/slider/layers/?utm_source=slider&utm_medium=free_plugin',
					'download_href' => 'https://demo.10web.io/wp-content/uploads/demo-sliders/layers.zip',
				),
	'online-store' => array(
					'name' => __('Online store', WDS()->prefix),
					'eye_href' => 'https://demo.10web.io/slider/online-store/?utm_source=slider&utm_medium=free_plugin',
					'download_href' => 'https://demo.10web.io/wp-content/uploads/demo-sliders/online-store.zip',
				),
	'hotspot' => array(
					'name' => __('HotSpot', WDS()->prefix),
					'eye_href' => 'https://demo.10web.io/slider/hotspot/?utm_source=slider&utm_medium=free_plugin',
					'download_href' => 'https://demo.10web.io/wp-content/uploads/demo-sliders/hotspot.zip',
				),
	'parallax' => array(
					'name' => __('Parallax', WDS()->prefix),
					'eye_href' => 'https://demo.10web.io/slider/parallax/?utm_source=slider&utm_medium=free_plugin',
					'download_href' => 'https://demo.10web.io/wp-content/uploads/demo-sliders/parallax.zip',
				),
	'filmstrip' => array(
					'name' => __('Filmstrip', WDS()->prefix),
					'eye_href' => 'https://demo.10web.io/slider/filmstrip/?utm_source=slider&utm_medium=free_plugin',
					'download_href' => 'https://demo.10web.io/wp-content/uploads/demo-sliders/filmstrip.zip',
				),
	'carousel' => array(
					'name' => __('Carousel', WDS()->prefix),
					'eye_href' => 'https://demo.10web.io/slider/carousel/?utm_source=slider&utm_medium=free_plugin',
					'download_href' => 'https://demo.10web.io/wp-content/uploads/demo-sliders/carousel.zip',
				),
	'slider-3d' => array(
					'name' => __('3D Slider', WDS()->prefix),
					'eye_href' => 'https://demo.10web.io/slider/3d-slider/?utm_source=slider&utm_medium=free_plugin',
					'download_href' => 'https://demo.10web.io/wp-content/uploads/demo-sliders/3d-slider.zip',
				),
	'zoom' => array(
					'name' => __('Zoom', WDS()->prefix),
					'eye_href' => 'https://demo.10web.io/slider/zoom/?utm_source=slider&utm_medium=free_plugin',
					'download_href' => 'https://demo.10web.io/wp-content/uploads/demo-sliders/zoom.zip',
				),
	'video' => array(
					'name' => __('Video', WDS()->prefix),
					'eye_href' => 'https://demo.10web.io/slider/video/?utm_source=slider&utm_medium=free_plugin',
					'download_href' => 'https://demo.10web.io/wp-content/uploads/demo-sliders/video.zip',
				)
  );
  ?>
  <div id="wd_download_popup" class="hidden">
	<div class="wd-download-popup-overlay" onclick="wd_download_popup(); return false;"></div>
	<div id="wd_download_popup_wrap" class="wd-download-popup-wrap">
		<span class="wd-popup-close" onclick="wd_download_popup(); return false;"></span>
		<div class="wd-download-text-wrap">
			<div class="icon"></div>
			<p><?php _e('Importing sliders is available in the Premium version only.', WDS()->prefix); ?></p>
		</div>
		<a href="<?php echo $buy_now_href; ?>" target="_blank" class="wds-buy-now-button"><?php _e('Buy Now', WDS()->prefix ); ?></a>
	</div>
  </div>
  <div class="wrap" id="main_featured_sliders_page">
    <div class="wd-table">
      <div class="wd-table-col wd-table-col-50 wd-table-col-left">
        <div class="wd-box-section">
          <div class="wd-box-title <?php echo ( WDS()->is_free ) ? 'wd-premium-title': '';?>">
            <div class="wd-table-col">
				<strong><?php _e('Import a slider', WDS()->prefix); ?></strong>
				<?php if ( WDS()->is_free ) { ?>
					<span><?php _e('This Feature is available in premium plugin.', WDS()->prefix); ?></span>
				<?php } ?>
			</div>
			<?php if ( WDS()->is_free ) { ?>
			<div class="wd-table-col wds-buy-now-button-wrap">
				<a href="<?php echo $buy_now_href; ?>" target="_blank" class="wds-buy-now-button"><?php _e('Buy Now', WDS()->prefix ); ?></a>
			</div>
			<?php } ?>
          </div>
          <div class="wd-box-content" style="<?php echo ( WDS()->is_free ) ? 'opacity: 0.3;': ''; ?>">
            <form method="post" enctype="multipart/form-data">
              <div class="wd-group">
                <input <?php echo ( WDS()->is_free || $error_ext_mess != '' ) ? 'disabled="disabled"' : ''; ?> type="file" name="fileimport" id="fileimport">
                <input <?php echo ( WDS()->is_free || $error_ext_mess != '' ) ? 'disabled="disabled"' : ''; ?> type="submit" name="wds_import_submit" class="button button-primary" onclick="<?php echo(WDS()->is_free ? 'alert(\'' . addslashes(__('This functionality is disabled in free version.', WDS()->prefix)) . '\'); return false;' : 'if(!wds_getfileextension(document.getElementById(\'fileimport\').value)){ return false; }'); ?>" value="<?php _e('Import', WDS()->prefix); ?>">
                <?php wp_nonce_field( WDS()->nonce, WDS()->nonce ); ?>
				<p class="description"><?php _e('Browse the .zip file of the slider.', WDS()->prefix); ?></p>
                <?php
                if ( $error_ext_mess != '' && !WDS()->is_free ) {
                  echo $error_ext_mess;
                }
                ?>
              </div>
            </form>
          </div>
        </div>
        <div class="wd-box-section">
          <div class="wd-box-title">
            <strong><?php _e('Download sliders', WDS()->prefix); ?></strong>
          </div>
          <div class="wd-box-content">
            <ul id="featured-sliders-list">
              <?php $i = 1; foreach ( $demo_sliders as $key => $slider ) { ?>
                <li class="<?php echo $key;?>">
                  <div class="product"></div>
				  <div class="title-download-wrap">
					<div class="title-wrap">
						<span class="name"><?php _e('Slider', WDS()->prefix); echo ' '. $i++; ?></span>
						<span class="slider-name"><?php echo $slider['name']; ?></span>
					</div>
					<div class="download-wrap">
						<span class="eye-wrap"><a target="_blank" href="<?php echo $slider['eye_href']; ?>" class="eye"></a></span>
						<a <?php echo ( WDS()->is_free ) ? 'onclick="wd_download_popup()"' : 'target="_blank" href="'. $slider['download_href'] .'"'; ?> class="download"><?php _e('Download', WDS()->prefix); ?></a>
					</div>
				  </div>
                </li>
                <?php } ?>
            </ul>
			<div style="clear: both;"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php
}