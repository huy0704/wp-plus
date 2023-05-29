<?php

namespace Nextend\Framework\Router\Base;

use Nextend\Framework\Router\Router;

class PlatformRouter {

    protected $router;

    /**
     * Router constructor.
     *
     * @param $router Router
     */
    public function __construct($router) {
        $this->router = $router;
    }

    public function setMultiSite() {

    }

    public function unSetMultiSite() {

    }
}