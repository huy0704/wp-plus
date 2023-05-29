<?php

use Nextend\SmartSlider3\Platform\SmartSlider3Platform;
use Nextend\SmartSlider3\SmartSlider3Info;

add_action('plugins_loaded', 'smart_slider_3_plugins_loaded', 30);

function smart_slider_3_plugins_loaded() {

    define('NEXTEND_SMARTSLIDER_3', dirname(__FILE__) . DIRECTORY_SEPARATOR);
    define('NEXTEND_SMARTSLIDER_3_BASENAME', NEXTEND_SMARTSLIDER_3_FREE_BASENAME);
    define('NEXTEND_SMARTSLIDER_3_SLUG', NEXTEND_SMARTSLIDER_3_FREE_SLUG);

    require_once dirname(__FILE__) . '/Defines.php';
    require_once(SMARTSLIDER3_LIBRARY_PATH . '/Autoloader.php');

    SmartSlider3Platform::getInstance();

    add_filter("plugin_action_links_" . NEXTEND_SMARTSLIDER_3_BASENAME, 'N2_SMARTSLIDER_3_UPGRADE_TO_PRO');
    function N2_SMARTSLIDER_3_UPGRADE_TO_PRO($links) {

        if (function_exists('is_plugin_active') && !is_plugin_active('nextend-smart-slider3-pro/nextend-smart-slider3-pro.php')) {
            if (!is_array($links)) {
                $links = array();
            }
            $params  = array(
                'utm_source'   => 'plugin-list',
                'utm_medium'   => 'smartslider-wordpress-free',
                'utm_campaign' => 'smartslider3'
            );
            $links[] = '<a href="' . SmartSlider3Info::getProUrlPricing($params) . '" style="color:#04C018;font-weight:bold;" target="_blank">' . "Go Pro" . '</a>';
        }

        return $links;
    }
}
