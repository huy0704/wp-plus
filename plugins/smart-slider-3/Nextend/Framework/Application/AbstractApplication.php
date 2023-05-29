<?php


namespace Nextend\Framework\Application;

use Nextend\Framework\Pattern\SingletonTrait;
use Nextend\Framework\Plugin;

abstract class AbstractApplication {

    use SingletonTrait;

    protected $key = '';

    protected function init() {

        //PluggableApplication\Nextend\SmartSlider3\Application\ApplicationSmartSlider3
        Plugin::doAction('PluggableApplication\\' . get_class($this), array($this));
    }

    public function getKey() {
        return $this->key;
    }

    public function enqueueAssets() {

    }
}