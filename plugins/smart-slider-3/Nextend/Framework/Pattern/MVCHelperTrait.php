<?php


namespace Nextend\Framework\Pattern;


use Nextend\Framework\Application\AbstractApplication;
use Nextend\Framework\Application\AbstractApplicationType;
use Nextend\Framework\Router\Router;

trait MVCHelperTrait {

    /** @var MVCHelperTrait */
    protected $MVCHelper;

    /**
     * @return Router
     */
    public function getRouter() {
        return $this->MVCHelper->getRouter();
    }

    /**
     * @param array|string $url
     * @param bool         $isPost
     * @param bool         $isAjax
     *
     * @return string
     */
    public function createUrl($url, $isPost = false, $isAjax = false) {
        return $this->MVCHelper->getRouter()
                               ->createUrl($url, $isPost, $isAjax);
    }

    /**
     * @param array|string $url
     *
     * @return string
     */
    public function createAjaxUrl($url) {
        return $this->MVCHelper->getRouter()
                               ->createAjaxUrl($url);
    }

    /**
     * @return AbstractApplication
     */
    public function getApplication() {

        return $this->MVCHelper->getApplication();
    }

    /**
     * @return AbstractApplicationType
     */
    public function getApplicationType() {
        return $this->MVCHelper->getApplicationType();
    }

    /**
     * @param MVCHelperTrait $helper
     *
     */
    protected function setMVCHelper($helper) {
        $this->MVCHelper = $helper;

        if (!method_exists($helper, 'getRouter') || !method_exists($helper, 'getApplication') || !method_exists($helper, 'getApplicationType')) {
            debug_print_backtrace();
            echo 'Object should has MVCHelperTrait';
            exit;
        }
    }

    /**
     * @return MVCHelperTrait
     */
    public function getMVCHelper() {
        return $this->MVCHelper;
    }
}