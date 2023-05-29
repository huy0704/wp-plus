<?php

namespace Nextend\SmartSlider3\Platform\WordPress\Integration\RankMath;

use Nextend\Framework\Pattern\GetAssetsPathTrait;
use Nextend\Framework\Plugin;
use Nextend\Framework\Request\Request;
use Nextend\SmartSlider3\Application\Admin\Sliders\ControllerAjaxSliders;
use Nextend\SmartSlider3\Application\Admin\TraitAdminUrl;
use Nextend\SmartSlider3\Application\ApplicationSmartSlider3;
use Nextend\SmartSlider3\SmartSlider3Info;

class RankMath {

    use GetAssetsPathTrait;
    use TraitAdminUrl;

    /** @var ControllerAjaxSliders */
    protected $controller;

    public function __construct() {

        if (class_exists('RankMath', false)) {
            add_action('admin_enqueue_scripts', array(
                $this,
                'admin_enqueue_scripts'
            ));

            Plugin::addAction('PluggableController\Nextend\SmartSlider3\Application\Admin\Sliders\ControllerAjaxSliders', array(
                $this,
                'plugControllerAjaxSliders'
            ));
        }
    }

    public function admin_enqueue_scripts($hook_suffix) {
        if (in_array($hook_suffix, array(
                'post.php',
                'post-new.php'
            ), true) && wp_script_is('rank-math-analyzer')) {

            $router = ApplicationSmartSlider3::getInstance()
                                             ->getApplicationTypeAdmin()
                                             ->getRouter();

            wp_enqueue_script('smart-slider-3-rank-math-integration', self::getAssetsUri() . '/dist/rank-math-integration.min.js', array(
                'wp-hooks',
                'rank-math-analyzer'
            ), SmartSlider3Info::$version, true);

            wp_localize_script('smart-slider-3-rank-math-integration', 'SmartSlider3RankMath', array(
                'adminAjaxUrl' => $router->createAjaxUrl(array(
                    'sliders/RankMathContent'
                ))
            ));
        }
    }

    /**
     * @param ControllerAjaxSliders $controller
     */
    public function plugControllerAjaxSliders($controller) {
        $this->controller = $controller;

        $this->controller->addExternalAction('rankmathcontent', array(
            $this,
            'actionRankMathContent'
        ));
    }

    public function actionRankMathContent() {

        $this->controller->validateToken();

        $sliderIDorAlias = Request::$POST->getInt('sliderID');
        if (empty($sliderIDorAlias)) {

            $sliderIDorAlias = Request::$POST->getVar('alias');
        }

        if (!empty($sliderIDorAlias)) {

            $applicationTypeFrontend = ApplicationSmartSlider3::getInstance()
                                                              ->getApplicationTypeFrontend();


            $applicationTypeFrontend->process('slider', 'display', false, array(
                'sliderID' => $sliderIDorAlias,
                'usage'    => 'RankMath ajax content'
            ));
        }

        exit;
    }
}