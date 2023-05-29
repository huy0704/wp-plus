<?php


namespace Nextend\SmartSlider3\Application;


use Exception;
use Nextend\Framework\Application\AbstractApplication;
use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Platform\Platform;
use Nextend\SmartSlider3\Application\Admin\ApplicationTypeAdmin;
use Nextend\SmartSlider3\Application\Frontend\ApplicationTypeFrontend;

class ApplicationSmartSlider3 extends AbstractApplication {

    protected $key = 'ss3';

    /** @var ApplicationTypeAdmin */
    protected $applicationTypeAdmin;

    /** @var ApplicationTypeFrontend */
    protected $applicationTypeFrontend;

    /**
     * @throws Exception
     */
    protected function init() {
        parent::init();

        $this->applicationTypeAdmin    = new ApplicationTypeAdmin($this);
        $this->applicationTypeFrontend = new ApplicationTypeFrontend($this);
    }

    /**
     * @return ApplicationTypeAdmin
     */
    public function getApplicationTypeAdmin() {

        return $this->applicationTypeAdmin;
    }

    /**
     * @return ApplicationTypeFrontend
     */
    public function getApplicationTypeFrontend() {

        return $this->applicationTypeFrontend;
    }

    public function enqueueAssets() {
        if (Platform::isAdmin()) {
            Js::addGlobalInline('window.N2SSPRO=' . N2SSPRO . ';');
        }
    
    }
}