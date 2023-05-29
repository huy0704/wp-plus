<?php


namespace Nextend\WordPress;


use Nextend\Framework\Pattern\SingletonTrait;

class OutputBuffer {

    use SingletonTrait;

    protected $priority = 100;

    protected $extraObStart = 0;

    protected function init() {

        add_action('init', array(
            $this,
            'onInit'
        ), $this->priority);

        add_action('shutdown', array(
            $this,
            'closeOutputBuffers'
        ), -1 * $this->priority);

        /**
         * Fix for KeyCDN cache enabled
         * @url https://wordpress.org/plugins/cache-enabler/
         */
        if (class_exists('Cache_Enabler', false)) {
            add_action('template_redirect', function () {
                ob_start(array(
                    $this,
                    "outputCallback"
                ));
            }, 0);
        }

        /**
         * Fix for Hyper Cache
         * @url https://wordpress.org/plugins/hyper-cache/
         */
        if (function_exists('hyper_cache_callback')) {
            add_filter('cache_buffer', array(
                $this,
                'prepareOutput'
            ));
        }

        /**
         * Fix for Speed booster Pack
         * @url https://wordpress.org/plugins/speed-booster-pack/
         */
        if (defined('SBP_VERSION')) {
            add_filter('sbp_output_buffer', array(
                $this,
                'prepareOutput'
            ));
        }

        if (class_exists('PagespeedNinja')) {
            /**
             * @see SSDEV-2358
             */
            add_action('template_redirect', function () {
                ob_start(array(
                    $this,
                    "outputCallback"
                ));
            });
        }

        if (class_exists('Cachify')) {
            /**
             * @see SSDEV-2776
             */
            add_action('template_redirect', function () {
                ob_start(array(
                    $this,
                    "outputCallback"
                ));
            });
        }

        if (class_exists('Ionos\Performance\Caching')) {
            /**
             * @see SSDEV-3780
             */
            add_action('template_redirect', function () {
                ob_start(array(
                    $this,
                    "outputCallback"
                ));
            });
        }

        if (defined('PERFMATTERS_VERSION')) {
            /**
             * @see SSDEV-3398
             */
            add_action('template_redirect', function () {
                ob_start(array(
                    $this,
                    "outputCallback"
                ));
            }, 11);
        }
    }

    /**
     * Theme's functions.php loaded at this point.
     */
    public function onInit() {

        /**
         * Borlabs cache
         * @url https://borlabs.io/download/
         */
        if (defined('BORLABS_CACHE_SLUG') && !is_admin()) {
            add_action('template_redirect', array(
                $this,
                'outputStart'
            ), -1 * $this->priority);

            return;
        }

        if (defined('THEMIFY_VERSION') && !is_admin()) {

            add_filter('template_include', array(
                $this,
                'templateIncludeOutputStart'
            ), 1); // Themify use priority: 0

            return;
        }

        add_action('pp_end_html', array(
            $this,
            'closeOutputBuffers'
        ), -10000); // ProPhoto 6 theme: we must close the buffer before the cache

        add_action('headway_html_close', array(
            $this,
            'closeOutputBuffers'
        ), $this->priority); // Headway theme

        $this->outputStart();
    }

    public function templateIncludeOutputStart($template) {

        $this->outputStart();

        return $template;
    }

    public function outputStart() {
        static $started = false;
        if ($started) {
            return true;
        }

        $started = true;

        if (defined('AUTOPTIMIZE_PLUGIN_DIR')) {
            add_filter('autoptimize_filter_html_before_minify', array(
                $this,
                'prepareOutput'
            ));
        }

        if (defined('WP_ROCKET_VERSION')) {
            add_filter('rocket_buffer', array(
                $this,
                'prepareOutput'
            ), -100000);
        }

        /**
         * Gantry 4 improvement to use the inbuilt output filter
         */
        if (defined('GANTRY_VERSION') && version_compare(GANTRY_VERSION, '4.0.0', '>=') && version_compare(GANTRY_VERSION, '5.0.0', '<')) {
            if (!is_admin()) {
                add_filter('gantry_before_render_output', array(
                    $this,
                    'prepareOutput'
                ));
                remove_action('shutdown', array(
                    $this,
                    'closeOutputBuffers'
                ), -1 * $this->priority);

                return true;
            }
        }

        ob_start(array(
            $this,
            "outputCallback"
        ));

        for ($i = 0; $i < $this->extraObStart; $i++) {
            ob_start();
        }

        /**
         * Ultimate reviews open a buffer on init and tries to close it on wp_footer.
         * To prevent that, lets open a new buffer which can be closed on wp_footer.
         *
         * @bug install Speed Contact Bar + Ultimate Reviews
         * @see https://wordpress.org/plugins/ultimate-reviews/
         */
        if (function_exists('EWD_URP_add_ob_start')) {
            ob_start();
        }

        /**
         * Cart66 closes our output buffer in forceDownload method
         * @url http://www.cart66.com
         */
        if (class_exists('Cart66')) {
            ob_start();
        }

        return true;
    }

    public function closeOutputBuffers() {

        if (!defined('WC_DOING_AJAX') || !WC_DOING_AJAX) {
            $handlers = ob_list_handlers();
            $callback = self::class . '::outputCallback';
            if (in_array($callback, $handlers)) {
                for ($i = count($handlers) - 1; $i >= 0; $i--) {
                    ob_end_flush();

                    if ($handlers[$i] === $callback) {
                        break;
                    }
                }
            }
        }
    }

    public function outputCallback($buffer, $phase) {

        if ($phase & PHP_OUTPUT_HANDLER_FINAL || $phase & PHP_OUTPUT_HANDLER_END) {
            return $this->prepareOutput($buffer);
        }

        return $buffer;
    }

    public function prepareOutput($buffer) {

        return apply_filters('wordpress_prepare_output', $buffer);
    }

    /**
     * @param int $priority
     */
    public function setPriority($priority) {
        $this->priority = $priority;
    }

    /**
     * @param int $extraObStart
     */
    public function setExtraObStart($extraObStart) {

        $this->extraObStart = $extraObStart;
    }
}