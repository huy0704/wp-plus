<?php


namespace Nextend\Framework\Application;


use Exception;
use Nextend\Framework\Controller\AbstractController;
use Nextend\Framework\Pattern\GetAssetsPathTrait;
use Nextend\Framework\Pattern\MVCHelperTrait;
use Nextend\Framework\Plugin;
use Nextend\Framework\Request\Request;
use Nextend\Framework\ResourceTranslator\ResourceTranslator;
use Nextend\Framework\Router\Router;
use Nextend\Framework\View\AbstractLayout;

abstract class AbstractApplicationType {

    use GetAssetsPathTrait, MVCHelperTrait;

    /** @var AbstractApplication */
    protected $application;

    /** @var Router */
    protected $router;

    protected $key = '';

    /** @var AbstractLayout */
    protected $layout;

    protected $externalControllers = array();

    /**
     * AbstractApplicationType constructor.
     *
     * @param AbstractApplication $application
     *
     * @throws Exception
     */
    public function __construct($application) {

        $this->setMVCHelper($this);

        $this->application = $application;

        ResourceTranslator::createResource('$' . $this->getKey() . '$', self::getAssetsPath(), self::getAssetsUri());

        $this->createRouter();


        //PluggableApplicationType\Nextend\SmartSlider3\Application\Admin\ApplicationTypeAdmin
        Plugin::doAction('PluggableApplicationType\\' . get_class($this), array($this));
    }

    public function getKey() {
        return $this->application->getKey() . '-' . $this->key;
    }

    protected function createRouter() {

    }

    public function processRequest($defaultControllerName, $defaultActionName, $ajax = false, $args = array()) {

        $controllerName = trim(Request::$REQUEST->getCmd("nextendcontroller"));
        if (empty($controllerName)) {
            $controllerName = $defaultControllerName;
        }

        $actionName = trim(Request::$REQUEST->getCmd("nextendaction"));
        if (empty($actionName)) {
            $actionName = $defaultActionName;
        }

        $this->process($controllerName, $actionName, $ajax, $args);
    }

    public function process($controllerName, $actionName, $ajax = false, $args = array()) {

        if ($ajax) {
            Request::$isAjax = true;
        }

        /** @var AbstractController $controller */
        $controller = $this->getController($controllerName, $ajax);

        $controller->doAction($actionName, $args);

    }

    /**
     * @param      $controllerName
     * @param bool $ajax
     *
     * @return AbstractController
     */
    protected function getController($controllerName, $ajax = false) {

        $methodName = 'getController' . ($ajax ? 'Ajax' : '') . $controllerName;

        if (method_exists($this, $methodName)) {

            return call_user_func(array(
                $this,
                $methodName
            ));
        } else if (isset($this->externalControllers[$controllerName])) {

            return call_user_func(array(
                $this->externalControllers[$controllerName],
                $methodName
            ));
        }

        return $this->getDefaultController($controllerName, $ajax);
    }

    protected abstract function getDefaultController($controllerName, $ajax = false);

    public function getApplication() {

        return $this->application;
    }

    public function getApplicationType() {
        return $this;
    }

    /**
     * @return Router
     */
    public function getRouter() {
        return $this->router;
    }

    public function enqueueAssets() {

        $this->application->enqueueAssets();
    }

    /**
     * @param AbstractLayout $layout
     */
    public function setLayout($layout) {
        $this->layout = $layout;
    }

    public function addExternalController($name, $source) {

        $this->externalControllers[$name] = $source;
    }

    public function getLogo() {

        return file_get_contents(self::getAssetsPath() . '/images/logo.svg');
    }
}