<?php


namespace Nextend\Framework\View;


use Nextend\Framework\Pattern\GetPathTrait;
use Nextend\Framework\Pattern\MVCHelperTrait;

abstract class AbstractBlock {

    use GetPathTrait;
    use MVCHelperTrait;

    /**
     * AbstractBlock constructor.
     *
     * @param MVCHelperTrait $MVCHelper
     */
    final public function __construct($MVCHelper) {

        $this->setMVCHelper($MVCHelper);

        $this->init();
    }

    protected function init() {

    }

    protected function renderTemplatePart($templateName) {

        include self::getPath() . '/' . $templateName . '.php';
    }

    /**
     * Returns the HTML code of the display method
     *
     * @return string
     */
    public function toHTML() {

        ob_start();

        $this->display();

        return ob_get_clean();
    }

    public abstract function display();
}