<?php

namespace Nextend\Framework\Router;

use Nextend\Framework\Form\Form;
use Nextend\Framework\Router\Base\PlatformRouter;
use Nextend\Framework\Router\WordPress\WordPressRouter;
use Nextend\Framework\Url\UrlHelper;

class Router {

    /**
     * @var Base\PlatformRouter
     */
    private $platformRouter;

    /**
     * @var string
     */
    protected $baseUrl;

    /**
     * @var bool|string
     */
    protected $baseUrlAjax;

    /**
     * @var bool|string
     */
    protected $networkUrl = false;

    /**
     * Router constructor.
     *
     * @param string      $baseUrl
     * @param string|bool $baseUrlAjax
     * @param string|bool $networkUrl
     */
    public function __construct($baseUrl, $baseUrlAjax = false, $networkUrl = false) {
        $this->platformRouter = new WordPressRouter($this);

        $this->baseUrl = $baseUrl;

        if ($baseUrlAjax === false) {
            $baseUrlAjax = UrlHelper::add_query_arg(array('nextendajax' => 1), $this->baseUrl);
        }
        $this->baseUrlAjax = $baseUrlAjax;

        $this->networkUrl = $networkUrl;
    }

    /**
     * @return string
     */
    public function getBaseUrl() {
        return $this->baseUrl;
    }

    /**
     * @param string $baseUrl
     */
    public function setBaseUrl($baseUrl) {
        $this->baseUrl = $baseUrl;
    }

    /**
     * @return bool|string
     */
    public function getNetworkUrl() {

        return $this->networkUrl;
    }

    /**
     * @param array|string $url
     * @param bool         $isPost
     * @param bool         $isAjax
     *
     * @return string
     */
    public function createUrl($url, $isPost = false, $isAjax = false) {
        //create url from array
        // [0] = controller/method
        // [1] = query parameters
        if (is_array($url)) {
            $href = $this->route($url[0], (isset($url[1]) ? $url[1] : array()), $isPost, $isAjax);
        } elseif (filter_var($url, FILTER_VALIDATE_URL)) {
            //completed url, no mods, just fun
            $href = $url;
        } elseif (strpos($url, "/") !== false) {
            //create url from string
            //format: controller/method
            $href = $this->route($url, array(), $isPost, $isAjax);
        } else {
            //fake link, replace to hashtag
            $href = "#";
        }

        return $href;
    }

    /**
     * @param array|string $url
     *
     * @return string
     */
    public function createAjaxUrl($url) {

        return $this->createUrl($url, false, true);
    }

    /**
     * @param       $url
     * @param array $queryArgs
     * @param bool  $isPost
     * @param bool  $isAjax
     *
     * @return string
     */
    private function route($url, $queryArgs = array(), $isPost = false, $isAjax = false) {

        if ($isAjax) {
            $baseUrl = $this->baseUrlAjax;
        } else {
            $baseUrl = $this->baseUrl;
        }

        $parsedAction = explode("/", $url);

        $queryArgs = array(
                'nextendcontroller' => strtolower(trim($parsedAction[0])),
                'nextendaction'     => strtolower(trim($parsedAction[1]))
            ) + $queryArgs;

        if ($isPost || $isAjax) {
            $queryArgs += Form::tokenizeUrl();
        }

        return UrlHelper::add_query_arg($queryArgs, $baseUrl);
    }

    public function setMultiSite() {

        $this->platformRouter->setMultiSite();
    }

    public function unSetMultiSite() {

        $this->platformRouter->unSetMultiSite();
    }
}