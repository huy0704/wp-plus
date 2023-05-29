<?php

namespace Nextend\SmartSlider3\Widget;

use Nextend\Framework\Pattern\PluggableTrait;
use Nextend\Framework\Pattern\SingletonTrait;
use Nextend\SmartSlider3\Widget\Group\AbstractWidgetGroup;
use Nextend\SmartSlider3\Widget\Group\Arrow;
use Nextend\SmartSlider3\Widget\Group\Autoplay;
use Nextend\SmartSlider3\Widget\Group\Bar;
use Nextend\SmartSlider3\Widget\Group\Bullet;
use Nextend\SmartSlider3\Widget\Group\Shadow;
use Nextend\SmartSlider3\Widget\Group\Thumbnail;

class WidgetGroupFactory {

    use SingletonTrait, PluggableTrait;

    /** @var AbstractWidgetGroup[] */
    private static $groups = array();

    protected function init() {

        new Arrow();
        new Autoplay();
        new Bar();
        new Bullet();
        new Shadow();
        new Thumbnail();

        $this->makePluggable('SliderWidgetGroup');
    }

    /**
     * @param AbstractWidgetGroup $group
     */
    public static function addGroup($group) {
        self::$groups[$group->getName()] = $group;
    }

    /**
     * @return AbstractWidgetGroup[]
     */
    public static function getGroups() {
        return self::$groups;
    }

    /**
     * @param $name
     *
     * @return AbstractWidgetGroup
     */
    public static function getGroup($name) {
        return self::$groups[$name];
    }

}

WidgetGroupFactory::getInstance();