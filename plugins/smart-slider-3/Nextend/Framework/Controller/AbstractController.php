<?php


namespace Nextend\Framework\Controller;


use Exception;
use Nextend\Framework\Acl\Acl;
use Nextend\Framework\Application\AbstractApplication;
use Nextend\Framework\Application\AbstractApplicationType;
use Nextend\Framework\Asset\AssetManager;
use Nextend\Framework\Asset\Predefined;
use Nextend\Framework\Form\Form;
use Nextend\Framework\Notification\Notification;
use Nextend\Framework\Pattern\GetPathTrait;
use Nextend\Framework\Pattern\MVCHelperTrait;
use Nextend\Framework\Plugin;
use Nextend\Framework\Request\Request;
use Nextend\SmartSlider3\Application\ApplicationSmartSlider3;

abstract class AbstractController {

    use GetPathTrait;
    use MVCHelperTrait;

    /**
     * @var AbstractApplicationType
     */
    protected $applicationType;

    /** @var callback[] */
    protected $externalActions = array();

    /**
     * AbstractController constructor.
     *
     * @param AbstractApplicationType $applicationType
     */
    public function __construct($applicationType) {

        //PluggableController\Nextend\SmartSlider3\Application\Admin\Slider\ControllerSlider
        Plugin::doAction('PluggableController\\' . get_class($this), array($this));


        $this->applicationType = $applicationType;
        $this->setMVCHelper($this->applicationType);

        AssetManager::getInstance();

        $this->initialize();
    }

    /**
     * @param          $actionName
     * @param callback $callable
     */
    public function addExternalAction($actionName, $callable) {

        $this->externalActions[$actionName] = $callable;
    }

    /**
     * @return AbstractApplication
     */
    public function getApplication() {
        return $this->applicationType->getApplication();
    }

    /**
     * @return AbstractApplicationType
     */
    public function getApplicationType() {
        return $this->applicationType;
    }

    public function getRouter() {
        return $this->applicationType->getRouter();
    }

    /**
     * @param       $actionName
     * @param array $args
     *
     * @throws Exception
     */
    final public function doAction($actionName, $args = array()) {

        $originalActionName = $actionName;

        if (method_exists($this, 'action' . $actionName)) {

            call_user_func_array(array(
                $this,
                'action' . $actionName
            ), $args);

        } else if (isset($this->externalActions[$actionName]) && is_callable($this->externalActions[$actionName])) {

            call_user_func_array($this->externalActions[$actionName], $args);

        } else {

            $actionName = $this->missingAction($this, $actionName);

            if (method_exists($this, 'action' . $actionName)) {

                call_user_func_array(array(
                    $this,
                    'action' . $actionName
                ), $args);

            } else {
                throw new Exception(sprintf('Missing action (%s) for controller (%s)', $originalActionName, static::class));
            }

        }
    }

    protected function missingAction($controllerName, $actionName) {

        return 'index';
    }

    public function initialize() {
        Predefined::frontend();
    }

    /**
     * Check ACL permissions
     *
     * @param      $action
     *
     * @return bool
     */
    public function canDo($action) {
        return Acl::canDo($action, $this);
    }

    public function redirect($url, $statusCode = 302, $terminate = true) {
        Request::redirect($url, $statusCode, $terminate);
    }

    public function validatePermission($permission) {

        if (!$this->canDo($permission)) {
            Notification::error(n2_('You are not authorised to view this resource.'));

            ApplicationSmartSlider3::getInstance()
                                   ->getApplicationTypeAdmin()
                                   ->process('sliders', 'index');

            return false;
        }

        return true;
    }

    public function validateVariable($condition, $property) {

        if (!$condition) {
            Notification::error(sprintf(n2_('Missing parameter: %s'), $property));

            ApplicationSmartSlider3::getInstance()
                                   ->getApplicationTypeAdmin()
                                   ->process('sliders', 'index');

            return false;
        }

        return true;
    }

    public function validateDatabase($condition, $showError = true) {
        if (!$condition) {
            if ($showError) {
                Notification::error(n2_('Database error'));

                ApplicationSmartSlider3::getInstance()
                                       ->getApplicationTypeAdmin()
                                       ->process('sliders', 'index');
            }

            return false;
        }

        return true;
    }

    public function validateToken() {
        if (!Form::checkToken()) {
            Notification::error(n2_('Security token mismatch'));

            return false;
        }

        return true;
    }
}