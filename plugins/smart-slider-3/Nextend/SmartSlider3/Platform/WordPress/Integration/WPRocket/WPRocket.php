<?php

namespace Nextend\SmartSlider3\Platform\WordPress\Integration\WPRocket;

use Nextend\Framework\Plugin;

class WPRocket {

    public function __construct() {

        if (defined('WP_ROCKET_VERSION')) {
            $this->init();

            if (function_exists('get_rocket_cdn_url') && function_exists("get_rocket_option")) {
                if (get_rocket_option('cdn', 0)) {
                    add_action('init', array(
                        $this,
                        'initCDN'
                    ));
                }
            }
        }
    }

    public function init() {

        /**
         * @see https://nextendweb.atlassian.net/browse/SSDEV-2335
         */
        add_filter('rocket_excluded_inline_js_content', array(
            $this,
            'remove_rocket_excluded_inline_js_content'
        ));

        /**
         * @see https://nextendweb.atlassian.net/browse/SSDEV-2434
         */
        add_filter('rocket_defer_inline_exclusions', array(
            $this,
            'rocket_defer_inline_exclusions'
        ));

        /**
         * @see https://nextendweb.atlassian.net/browse/SSDEV-3775
         */
        add_filter('rocket_delay_js_exclusions', array(
            $this,
            'rocket_delay_js_exclusions'
        ));
    }

    public function remove_rocket_excluded_inline_js_content($excluded_inline) {

        if (($index = array_search('SmartSliderSimple', $excluded_inline)) !== false) {
            array_splice($excluded_inline, $index, 1);
        }

        return $excluded_inline;
    }

    public function rocket_defer_inline_exclusions($inline_exclusions) {

        if (is_string($inline_exclusions)) {
            // Only for WP Rocket 3.8.0

            if (!empty($inline_exclusions)) {
                $inline_exclusions .= '|';
            }

            $inline_exclusions .= 'N2R';
        } else if (is_array($inline_exclusions)) {
            /**
             * Since WP Rocket 3.8.1 param is an array
             *
             * @see https://github.com/wp-media/wp-rocket/pull/3424
             */
            $inline_exclusions[] = 'N2R';
        }

        return $inline_exclusions;
    }

    public function rocket_delay_js_exclusions($exclude_delay_js) {

        $exclude_delay_js[] = '(.*)smart-slider(.*).js';
        $exclude_delay_js[] = 'new _N2';
        $exclude_delay_js[] = 'this._N2';

        return $exclude_delay_js;
    }

    public function initCDN() {
        Plugin::addFilter('n2_style_loader_src', array(
            $this,
            'filterSrcCDN'
        ));

        Plugin::addFilter('n2_script_loader_src', array(
            $this,
            'filterSrcCDN'
        ));
    }

    public function filterSrcCDN($src) {
        return get_rocket_cdn_url($src);
    }
}