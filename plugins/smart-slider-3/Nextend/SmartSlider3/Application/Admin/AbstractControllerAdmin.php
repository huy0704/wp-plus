<?php


namespace Nextend\SmartSlider3\Application\Admin;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Controller\Admin\AbstractAdminController;
use Nextend\Framework\Model\StorageSectionManager;
use Nextend\Framework\Platform\Platform;
use Nextend\Framework\Request\Request;
use Nextend\SmartSlider3\Application\Model\ModelSlidersXRef;
use Nextend\SmartSlider3\SmartSlider3Info;

abstract class AbstractControllerAdmin extends AbstractAdminController {

    use TraitAdminUrl;

    public function initialize() {
        parent::initialize();

        Js::addFirstCode('window.ss2lang = {};');

        require_once dirname(__FILE__) . '/JavaScriptTranslation.php';
    }

    public function loadSliderManager() {

        $groupID = Request::$REQUEST->getInt('sliderid', 0);

        $storage = StorageSectionManager::getStorage('smartslider');

        $options = array(
            'userEmail'      => Platform::getUserEmail(),
            'skipNewsletter' => intval($storage->get('free', 'subscribeOnImport')) || intval($storage->get('free', 'dismissNewsletterSampleSliders')),
            'exportAllUrl'   => $this->getUrlDashboardExportAll($groupID),
            'ajaxUrl'        => $this->getAjaxUrlSlidesCreate(),
            'previewUrl'     => $this->getUrlPreviewIndex(0),
            'importUrl'      => $this->getUrlImport($groupID),
            'paginationUrl'  => $this->getUrlPaginator()
        );

        Js::addInline("new _N2.ManageSliders('" . $groupID . "', " . json_encode($options) . ", " . json_encode(SmartSlider3Info::shouldSkipLicenseModal()) . ");");

    }

    public function redirectToSliders() {
        $this->redirect($this->getUrlDashboard());
    }

    /**
     * @param int $sliderID
     *
     * @return bool
     */
    protected function getGroupData($sliderID) {

        $groupID = Request::$REQUEST->getInt('groupID');

        $xref         = new ModelSlidersXRef($this);
        $groups       = $xref->getGroups($sliderID, 'published');
        $currentGroup = false;
        foreach ($groups as $group) {
            if ($group['group_id'] == $groupID) {
                $currentGroup = $group;
                break;
            }
        }
        if ($currentGroup === false) {
            $currentGroup = $groups[0];
        }

        return $currentGroup;
    }
}