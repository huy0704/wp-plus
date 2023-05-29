<?php


namespace Nextend\SmartSlider3;


use Nextend\Framework\Localization\Localization;
use Nextend\Framework\Pattern\GetAssetsPathTrait;
use Nextend\Framework\Pattern\SingletonTrait;
use Nextend\Framework\Platform\Platform;
use Nextend\Framework\Plugin;
use Nextend\Nextend;
use Nextend\SmartSlider3\Generator\Common\GeneratorCommonLoader;
use Nextend\SmartSlider3\Generator\Joomla\GeneratorJoomlaLoader;
use Nextend\SmartSlider3\Generator\WordPress\GeneratorWordPressLoader;
use Nextend\SmartSlider3\Platform\SmartSlider3Platform;
use Nextend\SmartSlider3\Slider\ResponsiveType\Auto\ResponsiveTypeAuto;
use Nextend\SmartSlider3\Slider\ResponsiveType\FullWidth\ResponsiveTypeFullWidth;
use Nextend\SmartSlider3\Slider\ResponsiveType\ResponsiveTypeFactory;
use Nextend\SmartSlider3\Slider\SliderType\Block\SliderTypeBlock;
use Nextend\SmartSlider3\Slider\SliderType\Simple\SliderTypeSimple;
use Nextend\SmartSlider3\Slider\SliderType\SliderTypeFactory;

class SmartSlider3 {

    use SingletonTrait, GetAssetsPathTrait;

    protected function init() {

        Platform::getInstance();
        SmartSlider3Platform::getInstance();

        Localization::loadPluginTextDomain(Nextend::getPath() . '/Languages');

        Storage::getInstance();

        Plugin::addAction('PluggableFactorySliderType', array(
            $this,
            'sliderTypes'
        ));

        Plugin::addAction('PluggableFactorySliderResponsiveType', array(
            $this,
            'sliderResponsiveTypes'
        ));

        Plugin::addAction('PluggableFactorySliderGenerator', array(
            $this,
            'sliderGenerator'
        ));


    }

    public function sliderTypes() {
        SliderTypeFactory::addType(new SliderTypeSimple());
        SliderTypeFactory::addType(new SliderTypeBlock());
    }

    public function sliderResponsiveTypes() {
        ResponsiveTypeFactory::addType(new ResponsiveTypeAuto());
        ResponsiveTypeFactory::addType(new ResponsiveTypeFullWidth());
    }

    public function sliderGenerator() {
        new GeneratorCommonLoader();
        new GeneratorWordPressLoader();
    }
}