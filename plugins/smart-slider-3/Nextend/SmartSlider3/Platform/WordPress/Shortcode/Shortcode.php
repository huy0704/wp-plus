<?php

namespace Nextend\SmartSlider3\Platform\WordPress\Shortcode;

use AMP_Options_Manager;
use AMP_Theme_Support;
use AmpProject\AmpWP\Option;
use Nextend\Framework\Asset\Builder\BuilderJs;
use Nextend\Framework\Localization\Localization;
use Nextend\Framework\Request\Request;
use Nextend\Framework\Sanitize;
use Nextend\Framework\View\Html;
use Nextend\SmartSlider3\Application\ApplicationSmartSlider3;
use Nextend\SmartSlider3\Application\Frontend\ApplicationTypeFrontend;

class Shortcode {

    private static $cacheSliderOutput = array();

    private static $iframe = false;

    private static $iframeReason = '';

    private static $disablePointer = false;

    private static $shortcodeMode = 'shortcode';

    public function __construct() {

        self::addShortCode();


        if (defined('DOING_AJAX') && DOING_AJAX) {
            if (isset($_POST['action']) && ($_POST['action'] == 'stpb_preview_builder_item' || $_POST['action'] == 'stpb_load_builder_templates' || $_POST['action'] == 'stpb_load_template')) {
                self::shortcodeModeToSkip();
            }
        }

        /**
         * There should not be sliders in the head
         */
        add_action('wp_head', array(
            self::class,
            'headStart'
        ), -10000);

        add_action('wp_head', array(
            self::class,
            'headEnd'
        ), 10000);


        /**
         * Thrive theme fix
         */
        add_action('before_theme_builder_template_render', array(
            self::class,
            'headEnd'
        ));

        add_action('woocommerce_shop_loop', array(
            self::class,
            'shortcodeModeToNoop'
        ), 9);
        add_action('woocommerce_shop_loop', array(
            self::class,
            'shortcodeModeToNormal'
        ), 11);

        add_action('woocommerce_single_product_summary', array(
            self::class,
            'shortcodeModeToNoop'
        ), 59);
        add_action('woocommerce_single_product_summary', array(
            self::class,
            'shortcodeModeToNormal'
        ), 61);

        /**
         * Remove Smart Slider from feeds
         */
        add_action('do_feed_rdf', array(
            self::class,
            'shortcodeModeToNoop'
        ), 0);
        add_action('do_feed_rss', array(
            self::class,
            'shortcodeModeToNoop'
        ), 0);
        add_action('do_feed_rss2', array(
            self::class,
            'shortcodeModeToNoop'
        ), 0);
        add_action('do_feed_atom', array(
            self::class,
            'shortcodeModeToNoop'
        ), 0);

        /**
         * Remove sliders from the news feed of Yandex.News Feed by Teplitsa
         * @url https://wordpress.org/plugins/yandexnews-feed-by-teplitsa/
         */
        add_filter('layf_content_feed', function ($content) {
            Shortcode::shortcodeModeToNoop();

            return $content;
        }, 1);

        /**
         * Sliders are not available over REST API! Fixes Gutenberg save problems.
         */
        add_action('rest_api_init', array(
            self::class,
            'shortcodeModeToNoop'
        ), 0);

        /**
         * Sometimes rest api initialized on the frontend, so we have prepare for that
         */
        add_action('wp', function () {
            remove_action('rest_api_init', array(
                self::class,
                'shortcodeModeToNoop'
            ), 0);
        });

        /**
         * Remove sliders from the AMP version of the site
         * @url https://wordpress.org/plugins/amp/
         */
        add_action('template_redirect', function () {
            if (function_exists('amp_is_request') && amp_is_request()) {
                add_action('wp_head', array(
                    self::class,
                    'shortcodeModeToNoop'
                ), 10001);

                /**
                 * Fix for reader mode
                 */
                if (class_exists('AMP_Theme_Support', false) && class_exists('AMP_Options_Manager', false)) {
                    if (AMP_Options_Manager::get_option(Option::THEME_SUPPORT) === AMP_Theme_Support::READER_MODE_SLUG) {
                        Shortcode::shortcodeModeToNoop();
                    }
                }
            }
        });

        /**
         * Remove sliders from the AMP version of the site
         * @url https://wordpress.org/plugins/weeblramp/
         */
        add_action('weeblramp_init', array(
            self::class,
            'shortcodeModeToNoop'
        ), 0);

        /**
         * Remove sliders from the Accelerated Mobile Pages version of the site
         * @url https://wordpress.org/plugins/accelerated-mobile-pages/
         * The other AMP plugin has a deprecated message for the "pre_amp_render_post" action, so we need to hook this function only for Accelerated Mobile Pages plugin.
         */
        if (defined('AMPFORWP_VERSION')) {
            add_action('pre_amp_render_post', array(
                self::class,
                'shortcodeModeToNoop'
            ));
        }


        add_action('after_setup_theme', function () {
            if (function_exists('KTT_share_args_for_posts')) {
                /**
                 * Theme: Narratium
                 * @url https://themeforest.net/item/narratium-simplicity-for-authors/20844434
                 */
                add_action('wp', array(
                    self::class,
                    'shortcodeModeToNoop'
                ), 0);
                add_action('wp', array(
                    self::class,
                    'shortcodeModeToNormal'
                ), 11);
            }


            if (class_exists('Themeco\Theme\Theme')) {
                /**
                 * @see SSDEV-3244
                 */
                remove_action('wp_head', array(
                    self::class,
                    'headStart'
                ), -10000);
            }
        });

    }

    public static function forceIframe($reason, $disablePointer = false) {
        self::$iframe         = true;
        self::$iframeReason   = $reason;
        self::$disablePointer = $disablePointer;
    }

    public static function doShortcode($parameters) {

        if (self::$shortcodeMode == 'noop') {
            return '';
        }

        if (Request::$isAjax) {
            return '';
        }

        if (!empty($parameters['alias'])) {
            $parameters['slider'] = $parameters['alias'];
        }

        if (isset($parameters['iframe'])) {
            self::forceIframe($parameters['iframe']);
        }

        if (self::$iframe) {
            if (isset($parameters['slider'])) {
                return self::renderIframe($parameters['slider']);
            }

            return 'Smart Slider - Please select a slider!';
        }

        return self::render($parameters);
    }

    /**
     * @param $sliderIDorAlias
     *
     * @return string contains escaped data
     */
    public static function renderIframe($sliderIDorAlias) {

        $path = ApplicationTypeFrontend::getAssetsPath() . '/dist/iframe.min.js';
        if (file_exists($path)) {
            $script = file_get_contents($path);
        } else {
        }


        $attributes = array(
            'class'       => "n2-ss-slider-frame intrinsic-ignore",
            'style'       => 'width:100%;max-width:none;display:block;border:0;opacity:0;' . (self::$disablePointer ? 'pointer-events:none;' : ''),
            'frameborder' => 0,
            'src'         => site_url('/') . '?n2prerender=1&n2app=smartslider&n2controller=slider&n2action=iframe&sliderid=' . $sliderIDorAlias . '&iseditor=' . (self::$iframeReason == 'ajax' ? 0 : 1) . '&hash=' . md5($sliderIDorAlias . NONCE_SALT)
        );
        $html       = '';

        switch (self::$iframeReason) {
            case 'divi':
                $attributes['onload'] = str_replace(array(
                        "\n",
                        "\r",
                        "\r\n",
                        '"',
                    ), array(
                        "",
                        "",
                        "",
                        "'"
                    ), $script) . 'n2SSIframeLoader(this);';
                break;
            case 'visualcomposer':
            default:
                $attributes['onload'] = str_replace(array(
                        "\n",
                        "\r",
                        "\r\n"
                    ), "", $script) . 'n2SSIframeLoader(this);';
                break;
        }

        return $html . '<div class="n2_ss_slider_frame_container">' . Html::tag('iframe', $attributes) . '</div>';
    }

    public static function render($parameters, $usage = 'WordPress Shortcode') {

        $parameters = shortcode_atts(array(
            'id'        => md5(time()),
            'slider'    => '',
            'logged_in' => null,
            'role'      => null,
            'cap'       => null,
            'page'      => null,
            'lang'      => null,
            'slide'     => null,
            'get'       => null
        ), $parameters);

        if (empty($parameters['slider'])) {
            return '';
        }

        if ($parameters['logged_in'] !== null) {
            $logged_in = !!$parameters['logged_in'];
            if (is_user_logged_in() !== $logged_in) {
                return '';
            }
        }

        if ($parameters['role'] !== null || $parameters['cap'] !== null) {
            $current_user = wp_get_current_user();

            if ($parameters['role'] !== null) {
                $current_user_roles = $current_user->roles;
                if (!in_array($parameters['role'], $current_user_roles)) {
                    return '';
                }
            }

            if ($parameters['cap'] !== null) {
                $current_user_caps = $current_user->allcaps;
                if (!isset($current_user_caps[$parameters['cap']]) || !$current_user_caps[$parameters['cap']]) {
                    return '';
                }
            }
        }

        if ($parameters['page'] !== null) {
            if ($parameters['page'] == 'home') {
                $condition = (!is_home() && !is_front_page());
            } else {
                $condition = ((get_the_ID() != intval($parameters['page'])) || (is_home() || is_front_page()));
            }
            if ($condition) {
                return '';
            }
        }

        if ($parameters['lang'] !== null) {
            if ($parameters['lang'] != Localization::getLocale()) {
                return '';
            }
        }

        if (!isset(self::$cacheSliderOutput[$parameters['slider']])) {

            if ((is_numeric($parameters['slider']) && intval($parameters['slider']) > 0) || !is_numeric($parameters['slider'])) {
                ob_start();

                $slideTo = false;
                if ($parameters['slide'] !== null) {
                    $slideTo = intval($parameters['slide']);
                }

                if ($parameters['get'] !== null) {
                    $slideTo = Request::$GET->getInt($parameters['get']);
                }

                if ($slideTo && is_numeric($parameters['slider']) && intval($parameters['slider']) > 0) {
                    echo wp_kses("<script>window['ss" . intval($parameters['slider']) . "'] = " . ($slideTo - 1) . ";</script>", Sanitize::$assetTags);
                }

                $applicationTypeFrontend = ApplicationSmartSlider3::getInstance()
                                                                  ->getApplicationTypeFrontend();

                $applicationTypeFrontend->process('slider', 'display', false, array(
                    'sliderID' => $parameters['slider'],
                    'usage'    => $usage
                ));

                self::$cacheSliderOutput[$parameters['slider']] = ob_get_clean();
            } else {
                return '';
            }
        }

        return self::$cacheSliderOutput[$parameters['slider']];
    }

    public static function changeShortcodeMode($mode) {
        if (self::$shortcodeMode != $mode) {
            self::$shortcodeMode = $mode;
        }
    }

    public static function shortcodeModeToNormal() {
        self::changeShortcodeMode('shortcode');
    }

    public static function shortcodeModeToNoop() {
        self::changeShortcodeMode('noop');
    }

    public static function shortcodeModeToSkip() {
        self::removeShortcode();
    }

    public static function shortcodeModeRestore() {
        self::addShortCode();
    }

    public static function addShortCode() {
        add_shortcode('smartslider3', array(
            self::class,
            'doShortcode'
        ));
    }

    private static function removeShortcode() {
        remove_shortcode('smartslider3');
    }

    public static function headStart() {
        self::shortcodeModeToNoop();

        add_action('wp_enqueue_scripts', array(
            self::class,
            'shortcodeModeToNormal'
        ), -1000000);
        add_action('wp_enqueue_scripts', array(
            self::class,
            'shortcodeModeToNoop'
        ), 1000000);

    }

    public static function headEnd() {

        remove_action('wp_enqueue_scripts', array(
            self::class,
            'shortcodeModeToNormal'
        ), -1000000);
        remove_action('wp_enqueue_scripts', array(
            self::class,
            'shortcodeModeToNoop'
        ), 1000000);

        self::shortcodeModeToNormal();
    }
}