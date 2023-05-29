<?php


namespace Nextend\Framework\View;


use Nextend\Framework\Controller\AbstractController;
use Nextend\Framework\Pattern\GetPathTrait;
use Nextend\Framework\Pattern\MVCHelperTrait;

abstract class AbstractViewAjax {

    use GetPathTrait;
    use MVCHelperTrait;

    /** @var AbstractController */
    protected $controller;

    /**
     * AbstractViewAjax constructor.
     *
     * @param AbstractController $controller
     *
     */
    public function __construct($controller) {
        $this->controller = $controller;

        $this->setMVCHelper($controller);
    }

    protected function render($templateName) {
        ob_start();
        include self::getPath() . '/Template/' . $templateName . '.php';

        return ob_get_clean();
    }

    /**
     * @return string
     */
    public abstract function display();
}