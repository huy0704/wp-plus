<?php


namespace Nextend\SmartSlider3\Platform;


use Nextend\Framework\Pattern\GetAssetsPathTrait;
use Nextend\Framework\Pattern\SingletonTrait;

abstract class AbstractSmartSlider3Platform {

    use SingletonTrait, GetAssetsPathTrait;

    public abstract function start();

    /**
     * @return string
     */
    public abstract function getAdminUrl();

    /**
     * @return string
     */
    public abstract function getAdminAjaxUrl();

    /**
     * @return string
     */
    public function getNetworkAdminUrl() {

        return $this->getAdminUrl();
    }
}