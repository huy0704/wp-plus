<?php


namespace Nextend\SmartSlider3\Application\Admin\Sliders;


use Nextend\Framework\Misc\Zip\Creator;
use Nextend\Framework\Model\StorageSectionManager;
use Nextend\Framework\PageFlow;
use Nextend\Framework\Request\Request;
use Nextend\SmartSlider3\Application\Admin\AbstractControllerAdmin;
use Nextend\SmartSlider3\Application\Admin\Sliders\Pro\ViewSlidersActivate;
use Nextend\SmartSlider3\Application\Model\ModelLicense;
use Nextend\SmartSlider3\Application\Model\ModelSliders;
use Nextend\SmartSlider3\BackupSlider\ExportSlider;
use Nextend\SmartSlider3\Settings;

class ControllerSliders extends AbstractControllerAdmin {

    protected function actionGettingStarted() {

        if (!StorageSectionManager::getStorage('smartslider')
                                  ->get('tutorial', 'GettingStarted')) {

            $view = new ViewSlidersGettingStarted($this);

            $view->display();
        } else {
            $this->redirectToSliders();
        
        }
    }

    protected function actionGettingStartedDontShow() {
        StorageSectionManager::getStorage('smartslider')
                             ->set('tutorial', 'GettingStarted', 1);

        $this->redirectToSliders();
    }

    protected function actionIndex() {
        $this->loadSliderManager();

        $view = new ViewSlidersIndex($this);
        $view->setPaginationIndex(max(0, intval(Request::$REQUEST->getInt('pageIndex', 0)) - 1));   /*-1 needs because beautified query string*/

        $view->display();
    }

    protected function actionTrash() {

        $view = new ViewSlidersTrash($this);

        $view->display();
    }

    protected function actionExportAll() {
        $slidersModel = new ModelSliders($this);
        $groupID      = (Request::$REQUEST->getVar('inSearch', false)) ? '*' : Request::$REQUEST->getInt('currentGroupID', 0);
        $sliders      = $slidersModel->getAll($groupID, 'published');
        $ids          = Request::$REQUEST->getVar('sliders');

        $files      = array();
        $saveAsFile = count($ids) == 1 ? false : true;
        foreach ($sliders as $slider) {
            if (!empty($ids) && !in_array($slider['id'], $ids)) {
                continue;
            }
            $export  = new ExportSlider($this, $slider['id']);
            $files[] = $export->create($saveAsFile);
        }

        $zip = new Creator();
        foreach ($files as $file) {
            $zip->addFile(file_get_contents($file), basename($file));
            unlink($file);
        }
        PageFlow::cleanOutputBuffers();
        header('Content-disposition: attachment; filename=sliders_unzip_to_import.zip');
        header('Content-type: application/zip');
        // PHPCS - Contains binary zip data, so nothing to escape.
        echo $zip->file(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        PageFlow::exitApplication();
    
    }

    protected function actionImport() {
        if ($this->validatePermission('smartslider_edit')) {

            $groupID = Request::$REQUEST->getVar('groupID', 0);

            $view = new ViewSlidersImport($this);
            $view->setGroupID($groupID);
            $view->display();
        }
    
    }
}