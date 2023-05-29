<?php


namespace Nextend\SmartSlider3\Slider\ResponsiveType;


use Nextend\Framework\Pattern\OrderableTrait;
use Nextend\Framework\Pattern\PluggableTrait;
use Nextend\Framework\Pattern\SingletonTrait;
use Nextend\SmartSlider3\Slider\Feature\Responsive;

class ResponsiveTypeFactory {

    use SingletonTrait, PluggableTrait, OrderableTrait;

    /**
     * @var AbstractResponsiveType[]
     */
    private static $types = array();

    /**
     * @param AbstractResponsiveType $responsiveType
     */
    public static function addType($responsiveType) {
        self::$types[$responsiveType->getName()] = $responsiveType;
    }

    /**
     * @param $name
     *
     * @return AbstractResponsiveType|null
     */
    public static function getType($name) {

        if (isset(self::$types[$name])) {
            return self::$types[$name];
        }

        if ($name == 'auto') {
            /**
             * There is no fallback if auto type missing
             */
            return null;
        }

        return self::getType('auto');
    }

    /**
     * @return AbstractResponsiveType[]
     */
    public static function getTypes() {

        return self::$types;
    }

    /**
     * @return AbstractResponsiveTypeAdmin[]
     */
    public static function getAdminTypes() {
        $adminTypes = array();

        foreach (self::$types as $name => $type) {
            $adminTypes[$name] = $type->createAdmin();
        }

        self::uasort($adminTypes);

        return $adminTypes;
    }

    protected function init() {

        $this->makePluggable('SliderResponsiveType');
    }

    /**
     * @param            $name
     * @param Responsive $responsive
     *
     * @return AbstractResponsiveTypeFrontend|null
     */
    public static function createFrontend($name, $responsive) {
        $type = self::getType($name);
        if ($type) {
            return $type->createFrontend($responsive);
        }

        return null;
    }
}

ResponsiveTypeFactory::getInstance();