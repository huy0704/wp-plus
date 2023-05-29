<?php


namespace Nextend\SmartSlider3\Slider\SliderType;


use Nextend\Framework\Pattern\OrderableTrait;
use Nextend\Framework\Pattern\PluggableTrait;
use Nextend\Framework\Pattern\SingletonTrait;
use Nextend\SmartSlider3\Slider\Slider;

class SliderTypeFactory {

    use SingletonTrait, PluggableTrait, OrderableTrait;

    /**
     * @var AbstractSliderType[]
     */
    private static $types = array();

    /**
     * @param AbstractSliderType $sliderType
     */
    public static function addType($sliderType) {
        self::$types[$sliderType->getName()] = $sliderType;
    }

    /**
     * @param $name
     *
     * @return AbstractSliderType|null
     */
    public static function getType($name) {

        if (isset(self::$types[$name])) {
            return self::$types[$name];
        }

        if ($name == 'simple') {
            /**
             * There is no fallback if simple type missing
             */
            return null;
        }

        return self::getType('simple');
    }

    /**
     * @return AbstractSliderType[]
     */
    public static function getTypes() {

        return self::$types;
    }

    /**
     * @return AbstractSliderTypeAdmin[]
     */
    public static function getAdminTypes() {
        $adminTypes = array();

        foreach (self::$types as $name => $type) {
            $admin = $type->createAdmin();
            if ($admin) {
                $adminTypes[$name] = $admin;
            }
        }

        self::uasort($adminTypes);

        return $adminTypes;
    }

    protected function init() {

        $this->makePluggable('SliderType');
    }

    /**
     * @param        $name
     * @param Slider $slider
     *
     * @return AbstractSliderTypeFrontend|null
     */
    public static function createFrontend($name, $slider) {
        $type = self::getType($name);
        if ($type) {
            return $type->createFrontend($slider);
        }

        return null;
    }

    /**
     * @param        $name
     * @param Slider $slider
     *
     * @return AbstractSliderTypeCss|null
     */
    public static function createCss($name, $slider) {
        $type = self::getType($name);
        if ($type) {
            return $type->createCss($slider);
        }

        return null;
    }
}

SliderTypeFactory::getInstance();