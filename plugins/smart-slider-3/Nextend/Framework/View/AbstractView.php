<?php


namespace Nextend\Framework\View;


use Nextend\Framework\Controller\AbstractController;
use Nextend\Framework\Pattern\GetPathTrait;
use Nextend\Framework\Pattern\MVCHelperTrait;

abstract class AbstractView {

    use GetPathTrait;
    use MVCHelperTrait;

    /** @var AbstractController */
    protected $controller;

    /** @var AbstractLayout */
    protected $layout;

    /**
     * AbstractView constructor.
     *
     * @param AbstractController $controller
     *
     */
    public function __construct($controller) {
        $this->controller = $controller;

        $this->setMVCHelper($controller);
    }

    /**
     * @param $templateName
     *
     * @return false|string output is a safe file, so nothing to escape.
     */
    protected function render($templateName) {
        ob_start();
        include self::getPath() . '/Template/' . $templateName . '.php';

        return ob_get_clean();
    }

    /**
     * @return AbstractController
     */
    public function getController() {
        return $this->controller;
    }

    public abstract function display();
}