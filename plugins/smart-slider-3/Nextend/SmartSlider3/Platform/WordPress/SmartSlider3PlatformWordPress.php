<?php

namespace Nextend\SmartSlider3\Platform\WordPress;

use Nextend\Framework\Asset\Predefined;
use Nextend\Framework\Sanitize;
use Nextend\Framework\WordPress\AssetInjector;
use Nextend\SmartSlider3\Application\ApplicationSmartSlider3;
use Nextend\SmartSlider3\Platform\AbstractSmartSlider3Platform;
use Nextend\SmartSlider3\Platform\WordPress\Admin\AdminHelper;
use Nextend\SmartSlider3\Platform\WordPress\Admin\Pro\WordPressUpdate;
use Nextend\SmartSlider3\Platform\WordPress\Integration\ACF\ACF;
use Nextend\SmartSlider3\Platform\WordPress\Integration\BeaverBuilder\BeaverBuilder;
use Nextend\SmartSlider3\Platform\WordPress\Integration\BoldGrid\BoldGrid;
use Nextend\SmartSlider3\Platform\WordPress\Integration\Brizy\Brizy;
use Nextend\SmartSlider3\Platform\WordPress\Integration\Divi\Divi;
use Nextend\SmartSlider3\Platform\WordPress\Integration\Elementor\Elementor;
use Nextend\SmartSlider3\Platform\WordPress\Integration\Fusion\Fusion;
use Nextend\SmartSlider3\Platform\WordPress\Integration\Gutenberg\Gutenberg;
use Nextend\SmartSlider3\Platform\WordPress\Integration\Jetpack\Jetpack;
use Nextend\SmartSlider3\Platform\WordPress\Integration\MotoPressCE\MotoPressCE;
use Nextend\SmartSlider3\Platform\WordPress\Integration\NimbleBuilder\NimbleBuilder;
use Nextend\SmartSlider3\Platform\WordPress\Integration\OxygenBuilder\OxygenBuilder;
use Nextend\SmartSlider3\Platform\WordPress\Integration\RankMath\RankMath;
use Nextend\SmartSlider3\Platform\WordPress\Integration\TablePress\TablePress;
use Nextend\SmartSlider3\Platform\WordPress\Integration\TatsuBuilder\TatsuBuilder;
use Nextend\SmartSlider3\Platform\WordPress\Integration\ThemifyBuilder\ThemifyBuilder;
use Nextend\SmartSlider3\Platform\WordPress\Integration\Unyson\Unyson;
use Nextend\SmartSlider3\Platform\WordPress\Integration\VisualComposer1\VisualComposer1;
use Nextend\SmartSlider3\Platform\WordPress\Integration\VisualComposer2\VisualComposer2;
use Nextend\SmartSlider3\Platform\WordPress\Integration\WPRocket\WPRocket;
use Nextend\SmartSlider3\Platform\WordPress\Shortcode\Shortcode;
use Nextend\SmartSlider3\Platform\WordPress\Widget\WidgetHelper;
use Nextend\SmartSlider3\PublicApi\Project;

class SmartSlider3PlatformWordPress extends AbstractSmartSlider3Platform {

    public function start() {

        require_once dirname(__FILE__) . '/compat.php';

        $helperInstall = new HelperInstall();
        $helperInstall->installOrUpgrade();

        new WidgetHelper();
        new Shortcode();

        new AdminHelper();

        add_action('admin_head', function () {

            if (wp_script_is('gutenberg-smartslider3')) {

                Predefined::frontend();
                Predefined::backend();
                ApplicationSmartSlider3::getInstance()
                                       ->getApplicationTypeAdmin()
                                       ->enqueueAssets();
            }
        });

        new WordPressFrontend();

        AssetInjector::getInstance();

        $this->integrate();

        $this->initSanitize();
    }

    public function getAdminUrl() {

        return admin_url("admin.php?page=" . NEXTEND_SMARTSLIDER_3_URL_PATH);
    }

    public function getAdminAjaxUrl() {

        return add_query_arg(array('action' => NEXTEND_SMARTSLIDER_3_URL_PATH), admin_url('admin-ajax.php'));
    }

    public function getNetworkAdminUrl() {

        return network_admin_url("admin.php?page=" . NEXTEND_SMARTSLIDER_3_URL_PATH);
    }

    private function integrate() {

        new Compatibility();

        new TablePress();

        new Gutenberg();

        HelperTinyMCE::getInstance();

        /**
         * Advanced Custom Fields
         */
        new ACF();

        new Divi();

        new VisualComposer1();

        new VisualComposer2();

        new Elementor();

        new MotoPressCE();

        new BeaverBuilder();

        new Jetpack();

        new Fusion();

        new WPRocket();

        new Unyson();

        new OxygenBuilder();

        new NimbleBuilder();

        new Brizy();

        new BoldGrid();

        new RankMath();

        new ThemifyBuilder();

        new TatsuBuilder();
    }

    private function initSanitize() {
        Sanitize::set_allowed_tags();
    }

    /**
     * @param $file
     *
     * @return bool|int
     *
     * @deprecated
     */
    public static function importSlider($file) {

        return Project::import($file);
    }
}