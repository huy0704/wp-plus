<?php


namespace Nextend\SmartSlider3\Application\Admin;


use Exception;
use Nextend\Framework\Application\AbstractApplicationType;
use Nextend\Framework\Asset\Css\Css;
use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Browse\ControllerAjaxBrowse;
use Nextend\Framework\Content\ControllerAjaxContent;
use Nextend\Framework\Font\ControllerAjaxFont;
use Nextend\Framework\Image\ControllerAjaxImage;
use Nextend\Framework\Image\Image;
use Nextend\Framework\Platform\Platform;
use Nextend\Framework\Router\Router;
use Nextend\Framework\Style\ControllerAjaxStyle;
use Nextend\SmartSlider3\Application\Admin\Generator\ControllerAjaxGenerator;
use Nextend\SmartSlider3\Application\Admin\Generator\ControllerGenerator;
use Nextend\SmartSlider3\Application\Admin\GoPro\ControllerGoPro;
use Nextend\SmartSlider3\Application\Admin\Help\ControllerHelp;
use Nextend\SmartSlider3\Application\Admin\Layout\AbstractLayoutMenu;
use Nextend\SmartSlider3\Application\Admin\Preview\ControllerPreview;
use Nextend\SmartSlider3\Application\Admin\Settings\ControllerAjaxSettings;
use Nextend\SmartSlider3\Application\Admin\Settings\ControllerSettings;
use Nextend\SmartSlider3\Application\Admin\Slider\ControllerAjaxSlider;
use Nextend\SmartSlider3\Application\Admin\Slider\ControllerSlider;
use Nextend\SmartSlider3\Application\Admin\Sliders\ControllerAjaxSliders;
use Nextend\SmartSlider3\Application\Admin\Sliders\ControllerSliders;
use Nextend\SmartSlider3\Application\Admin\Slides\ControllerAjaxSlides;
use Nextend\SmartSlider3\Application\Admin\Slides\ControllerSlides;
use Nextend\SmartSlider3\Application\Admin\Update\ControllerUpdate;
use Nextend\SmartSlider3\Application\Admin\Visuals\ControllerAjaxCss;
use Nextend\SmartSlider3\BackgroundAnimation\ControllerAjaxBackgroundAnimation;
use Nextend\SmartSlider3\Platform\Joomla\JoomlaShim;
use Nextend\SmartSlider3\Platform\SmartSlider3Platform;
use Nextend\SmartSlider3\SmartSlider3Info;

class ApplicationTypeAdmin extends AbstractApplicationType {

    use TraitAdminUrl;

    protected $key = 'admin';

    protected function createRouter() {

        $this->router = new Router(SmartSlider3Platform::getAdminUrl(), SmartSlider3Platform::getAdminAjaxUrl(), SmartSlider3Platform::getNetworkAdminUrl());
    }

    public function setLayout($layout) {
        parent::setLayout($layout);

        if ($this->layout instanceof AbstractLayoutMenu) {
            $this->layout->addBreadcrumb(n2_('Dashboard'), 'ssi_16 ssi_16--dashboard', $this->getUrlDashboard());
        }

        Js::addGlobalInline("window.N2SS3VERSION='" . SmartSlider3Info::$version . "';");
    }

    protected function getControllerSliders() {

        return new ControllerSliders($this);
    }

    protected function getControllerAjaxSliders() {

        return new ControllerAjaxSliders($this);
    }

    protected function getControllerSlider() {

        return new ControllerSlider($this);
    }

    protected function getControllerAjaxSlider() {

        return new ControllerAjaxSlider($this);
    }

    protected function getControllerSlides() {

        return new ControllerSlides($this);
    }

    protected function getControllerAjaxSlides() {

        return new ControllerAjaxSlides($this);
    }

    protected function getControllerGenerator() {

        return new ControllerGenerator($this);
    }

    protected function getControllerAjaxGenerator() {

        return new ControllerAjaxGenerator($this);
    }

    protected function getControllerPreview() {

        return new ControllerPreview($this);
    }

    protected function getControllerSettings() {

        return new ControllerSettings($this);
    }

    protected function getControllerAjaxSettings() {

        return new ControllerAjaxSettings($this);
    }

    protected function getControllerHelp() {

        return new ControllerHelp($this);
    }

    protected function getControllerGoPro() {

        return new ControllerGoPro($this);
    }

    protected function getControllerAjaxBackgroundAnimation() {

        return new ControllerAjaxBackgroundAnimation($this);
    }

    protected function getControllerAjaxFont() {

        return new ControllerAjaxFont($this);
    }

    protected function getControllerAjaxStyle() {

        return new ControllerAjaxStyle($this);
    }

    protected function getControllerAjaxCss() {

        return new ControllerAjaxCss($this);
    }

    protected function getControllerAjaxImage() {

        return new ControllerAjaxImage($this);
    }

    protected function getControllerAjaxContent() {

        return new ControllerAjaxContent($this);
    }

    protected function getControllerUpdate() {

        return new ControllerUpdate($this);
    }

    protected function getControllerAjaxBrowse() {

        return new ControllerAjaxBrowse($this);
    }


    protected function getDefaultController($controllerName, $ajax = false) {

        if ($controllerName !== 'sliders') {
            return $this->getControllerSliders();
        }

        throw new Exception('Missing default controller for application type.');
    }

    public function enqueueAssets() {

        Js::addInline('_N2.AjaxHelper.addAdminUrl(' . json_encode($this->getKey()) . ', ' . json_encode($this->createAjaxUrl('/')) . ');');


        JS::addInline('_N2.BrowserCompatibility(' . json_encode($this->getUrlHelpBrowserIncompatible()) . ');');

        parent::enqueueAssets();
        if (Platform::isAdmin()) {
            Js::addGlobalInline('window.N2SS3C="' . SmartSlider3Info::$campaign . '";');
        }

        Image::enqueueHelper();

        static $once;
        if ($once != null) {
            return;
        }
        $once = true;

        $path = self::getAssetsPath();

        Css::addStaticGroup($path . '/dist/smartslider-admin.min.css', 'smartslider-admin');

        Js::addStaticGroup($path . '/dist/smartslider-backend.min.js', 'smartslider-backend');
    }
}