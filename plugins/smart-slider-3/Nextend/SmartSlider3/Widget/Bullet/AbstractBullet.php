<?php


namespace Nextend\SmartSlider3\Widget\Bullet;


use Nextend\SmartSlider3\Widget\AbstractWidget;

abstract class AbstractBullet extends AbstractWidget {

    protected $key = 'widget-bullet-';

    public function getCommonAssetsPath() {
        return dirname(__FILE__) . '/Assets';
    }
}