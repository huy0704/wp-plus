<?php


namespace Nextend\SmartSlider3\Platform\WordPress;


use Nextend\Framework\Request\Request;
use Nextend\SmartSlider3\Platform\WordPress\Shortcode\Shortcode;
use Nextend\SmartSlider3\Settings;

class Compatibility {

    public function __construct() {

        /**
         * Fix for NextGenGallery and Divi live editor bug
         */
        add_filter('run_ngg_resource_manager', function ($ret) {
            if (Request::$GET->getInt('n2prerender') && Request::$GET->getCmd('n2app') !== '') {
                $ret = false;
            }

            return $ret;
        }, 1000000);


        /**
         * For ajax based page loaders
         *
         * HTTP_X_BARBA -> Rubenz theme
         * swup -> Etc @see https://themeforest.net/item/etc-agency-freelance-portfolio-wordpress-theme/23832736
         */

        $xRequestedWiths = array(
            'XMLHttpRequest',
            'swup'
        );

        if ((Request::$SERVER->getCmd('HTTP_X_REQUESTED_WITH') !== '' && in_array(Request::$SERVER->getCmd('HTTP_X_REQUESTED_WITH'), $xRequestedWiths)) || Request::$SERVER->getCmd('HTTP_X_BARBA') !== '') {

            if (intval(Settings::get('wp-ajax-iframe-slider', 0))) {
                Shortcode::forceIframe('ajax');
            }
        }


        add_action('load-toplevel_page_' . NEXTEND_SMARTSLIDER_3_URL_PATH, array(
            $this,
            'removeEmoji'
        ));


        /**
         * Yoast SEO - Sitemap add images
         */
        if (Settings::get('yoast-sitemap', 1)) {
            add_filter('wpseo_xml_sitemap_post_url', array(
                $this,
                'filter_wpseo_xml_sitemap_post_url'
            ), 10, 2);
        }


        /**
         * Not sure which page builder is it...
         */
        if (Request::$GET->getInt('pswLoad')) {
            Shortcode::forceIframe('psw');
        }

        if (defined('WC_ETRANSACTIONS_PLUGIN')) {
            /**
             * Plugin: https://wordpress.org/plugins/e-transactions-wc/
             *
             * @see SSDEV-2680
             */
            remove_action('admin_notices', 'hmac_admin_notice');
        }

        /**
         * Plugin: https://wordpress.org/plugins/weglot/
         *
         * @see SSDEV-3551
         */
        if (defined('WEGLOT_NAME') && Request::$GET->getInt('n2prerender') && Request::$GET->getCmd('n2app') !== '') {
            add_filter('weglot_button_html', function ($button_html) {
                return '';
            });
        }
    }

    public function removeEmoji() {

        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
    }

    public static function filter_wpseo_xml_sitemap_post_url($permalink, $post) {
        global $shortcode_tags;
        $_shortcode_tags    = $shortcode_tags;
        $shortcode_tags     = array(
            "smartslider3" => array(
                Shortcode::class,
                "doShortcode"
            )
        );
        $post->post_content = do_shortcode($post->post_content);
        $shortcode_tags     = $_shortcode_tags;

        return $permalink;
    }
}