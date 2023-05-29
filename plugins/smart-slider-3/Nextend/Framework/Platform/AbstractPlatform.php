<?php


namespace Nextend\Framework\Platform;


use Nextend\Framework\Pattern\GetAssetsPathTrait;

abstract class AbstractPlatform {

    use GetAssetsPathTrait;

    protected $isAdmin = false;

    protected $hasPosts = false;

    public function isAdmin() {

        return $this->isAdmin;
    }

    public function setIsAdmin($isAdmin) {
        $this->isAdmin = $isAdmin;
    }

    public abstract function getName();

    public abstract function getLabel();

    public abstract function getVersion();

    public function hasPosts() {
        return $this->hasPosts;
    }

    public abstract function getSiteUrl();

    public function getCharset() {
        return 'UTF-8';
    }

    public function getMysqlDate() {
        return date("Y-m-d H:i:s");
    }

    public function getTimestamp() {

        return time();
    }

    /**
     * @return string
     */
    public abstract function getPublicDirectory();

    public function getUserEmail() {

        return '';
    }

    public function needStrongerCss() {

        return false;
    }

    public function getDebug() {

        return array();
    }

    public function filterAssetsPath($assetsPath) {

        return $assetsPath;
    }
}