<?php


namespace Nextend\SmartSlider3\Application\Admin\Sliders;


use Nextend\Framework\Controller\Admin\AdminAjaxController;
use Nextend\Framework\Data\Data;
use Nextend\Framework\Filesystem\Filesystem;
use Nextend\Framework\Misc\HttpClient;
use Nextend\Framework\Model\StorageSectionManager;
use Nextend\Framework\Notification\Notification;
use Nextend\Framework\Platform\Platform;
use Nextend\Framework\Request\Request;
use Nextend\Framework\ResourceTranslator\ResourceTranslator;
use Nextend\SmartSlider3\Application\Admin\TraitAdminUrl;
use Nextend\SmartSlider3\Application\Model\ModelSliders;
use Nextend\SmartSlider3\BackupSlider\ImportSlider;
use Nextend\SmartSlider3\Settings;
use RuntimeException;

class ControllerAjaxSliders extends AdminAjaxController {

    use TraitAdminUrl;

    public function actionList() {
        $this->validateToken();

        $parentID = Request::$REQUEST->getInt('parentID');
        $this->validateVariable($parentID >= 0, 'parentID');

        if ($parentID > 0) {
            $orderBy          = 'ordering';
            $orderByDirection = 'ASC';
        } else {
            $orderBy          = Settings::get('slidersOrder2', 'ordering');
            $orderByDirection = Settings::get('slidersOrder2Direction', 'ASC');
        }

        $slidersModel = new ModelSliders($this);
        $sliders      = $slidersModel->getAll($parentID, 'published', $orderBy, $orderByDirection);

        $data = array();
        foreach ($sliders as $slider) {
            $data[] = array(
                'id'            => $slider['id'],
                'alias'         => $slider['alias'],
                'title'         => $slider['title'],
                'thumbnail'     => $this->getSliderThumbnail($slider),
                'isGroup'       => $slider['type'] == 'group',
                'childrenCount' => $slider['slides'] > 0 ? $slider['slides'] : 0
            );
        }

        $this->response->respond($data);
    }

    private function getSliderThumbnail($slider) {

        $thumbnail = $slider['thumbnail'];
        if (empty($thumbnail)) {
            return '';
        } else {
            return ResourceTranslator::toUrl($thumbnail);
        }
    }

    public function actionOrder() {
        $this->validateToken();

        $this->validatePermission('smartslider_edit');

        $slidersModel = new ModelSliders($this);
        $result       = $slidersModel->order(Request::$REQUEST->getVar('groupID', 0), Request::$REQUEST->getVar('sliderorder'), Request::$REQUEST->getInt('isReversed', 1), Request::$REQUEST->getVar('orders', array()));
        $this->validateDatabase($result);

        Notification::success(n2_('Slider order saved.'));
        $this->response->respond();
    }

    public function actionTrash() {
        $this->validateToken();

        $this->validatePermission('smartslider_delete');

        $groupID = Request::$REQUEST->getInt('groupID', 0);
        $this->validateVariable($groupID >= 0, 'groupID');

        $ids = array_map('intval', array_filter((array)Request::$REQUEST->getVar('sliders'), 'is_numeric'));

        $this->validateVariable(count($ids), 'Slider');

        $slidersModel = new ModelSliders($this);

        $isTrash  = false;
        $isUnlink = false;
        foreach ($ids as $id) {
            if ($id > 0) {
                $mode = $slidersModel->trash($id, $groupID);
                switch ($mode) {
                    case 'trash':
                        $isTrash = true;
                        break;
                    case 'unlink':
                        $isUnlink = true;
                        break;
                }
            }
        }

        if ($isTrash) {
            Notification::success(n2_('Slider(s) moved to the trash.'));
        }

        if ($isUnlink) {
            Notification::success(n2_('Slider(s) removed from the group.'));
        }

        $this->response->respond();
    }

    public function actionEmptyTrash() {
        $this->validateToken();

        $this->validatePermission('smartslider_delete');

        $slidersModel = new ModelSliders($this);

        $slidersInTrash = $slidersModel->getAll('*', 'trash');

        foreach ($slidersInTrash as $slider) {
            $slidersModel->deletePermanently($slider['id']);
        }

        Notification::success(n2_('Slider(s) deleted permanently from the trash.'));

        $this->response->respond();
    }

    public function actionHideReview() {
        $this->validateToken();

        $this->validatePermission('smartslider_config');

        StorageSectionManager::getStorage('smartslider')
                             ->set('free', 'review', 1);

        $this->response->respond();
    }

    public function actionSearch() {
        $this->validateToken();

        $this->validatePermission('smartslider_config');

        $slidersModel = new ModelSliders($this);

        $keyword = Request::$REQUEST->getVar('keyword', '');
        $sliders = array();

        $url     = parse_url($keyword);
        $baseUrl = parse_url(Platform::getSiteUrl());

        if (isset($url['host']) && $url['host'] === $baseUrl['host']) {
            $content = HttpClient::get($keyword);
            preg_match_all('/data-ssid="(?<id>[0-9]+)/', $content, $matches);

            foreach ($matches['id'] as $sliderID) {
                if ($_slider = $slidersModel->getWithThumbnail($sliderID)) {
                    array_push($sliders, $_slider);
                }
            }
        }

        $sliders = array_merge($sliders, $slidersModel->getSearchResults($keyword));
        $result  = array();
        if (!empty($sliders)) {
            foreach ($sliders as $slider) {
                $result[] = array(
                    'id'            => $slider['id'],
                    'alias'         => $slider['alias'],
                    'title'         => $slider['title'],
                    'thumbnail'     => $this->getSliderThumbnail($slider),
                    'isGroup'       => $slider['type'] == 'group',
                    'childrenCount' => $slider['slides'] > 0 ? $slider['slides'] : 0,
                    'editUrl'       => $this->getUrlSliderEdit($slider['id'], $slider['group_id']),
                    'order'         => $slider['ordering']
                );
            }
        }

        $this->response->respond($result);

    }

    public function actionPagination() {
        $this->validateToken();

        $this->validatePermission('smartslider_config');

        $slidersModel   = new ModelSliders($this);
        $pageIndex      = Request::$REQUEST->getInt('pageIndex', 0);
        $limit          = Request::$REQUEST->getVar('limit', 20);
        $orderBy        = Request::$REQUEST->getCmd('orderBy', 'ordering');
        $orderDirection = Request::$REQUEST->getCmd('orderDirection', 'ASC');

        Settings::set('limit', $limit);
        Settings::set('slidersOrder2', $orderBy);
        Settings::set('slidersOrder2Direction', $orderDirection);

        if ($pageIndex < 0) {
            $pageIndex = 0;
        }

        $sliderCount = $slidersModel->getSlidersCount('published', true);
        $result      = array();

        $sliders = $slidersModel->getAll(0, 'published', $orderBy, $orderDirection, $pageIndex, $limit);

        //if last page is empty
        if (empty($sliders) && $sliderCount) {
            $lastPageIndex       = intval(ceil(($sliderCount - $limit) / $limit));
            $sliders             = $slidersModel->getAll(0, 'published', $orderBy, $orderDirection, $lastPageIndex, $limit);
            $result['pageIndex'] = $lastPageIndex;
        }

        if (!empty($sliders)) {
            foreach ($sliders as $slider) {
                $result['sliders'][] = array(
                    'id'            => $slider['id'],
                    'alias'         => $slider['alias'],
                    'title'         => $slider['title'],
                    'thumbnail'     => $this->getSliderThumbnail($slider),
                    'isGroup'       => $slider['type'] == 'group',
                    'childrenCount' => $slider['slides'] > 0 ? $slider['slides'] : 0,
                    'editUrl'       => $this->getUrlSliderEdit($slider['id'], 0),
                    'order'         => $slider['ordering']
                );
            }
            $result['slidersPerPage'] = count($sliders);
        }
        $result['sliderCount'] = $sliderCount;

        $this->response->respond($result);
    }

    protected function actionImport() {

        $this->validateToken();

        $this->validatePermission('smartslider_edit');

        if (empty($_FILES) && empty($_POST)) {
            Notification::error(sprintf(n2_('Your server has an upload file limit at %s, so if you have bigger export file, please use the local import file method.'), @ini_get('post_max_size')));
            $this->response->respond();
        } else if (!empty($_POST)) {
            $data = new Data(Request::$REQUEST->getVar('slider'));


            $restore = $data->get('restore', 0);

            $file = '';

            $slider = Request::$FILES->getVar('slider');

            if ($slider['tmp_name']['import-file'] !== null) {

                switch ($slider['error']['import-file']) {
                    case UPLOAD_ERR_OK:
                    case UPLOAD_ERR_NO_FILE:
                        break;
                    case UPLOAD_ERR_INI_SIZE:
                    case UPLOAD_ERR_FORM_SIZE:
                        throw new RuntimeException('Exceeded filesize limit.');
                    default:
                        throw new RuntimeException('Unknown errors.');
                }

                $file = $slider['tmp_name']['import-file'];
            }

            if (empty($file)) {
                $_file = $data->get('local-import-file');
                if (!empty($_file)) {
                    $file = Platform::getPublicDirectory() . '/' . $_file;
                }
            }

            if (Filesystem::fileexists($file)) {

                $import = new ImportSlider($this);
                if ($restore) {
                    $import->enableReplace();
                }

                $groupID = Request::$REQUEST->getVar('groupID', 0);

                $sliderId = $import->import($file, $groupID, $data->get('image-mode', 'clone'), 0);

                if ($sliderId !== false) {
                    Notification::success(n2_('Slider imported.'));

                    if ($data->get('delete')) {
                        @unlink($file);
                    }

                    $this->response->redirect($this->getUrlSliderEdit($sliderId, $groupID));
                } else {
                    $extension = pathinfo($slider['name']['import-file'], PATHINFO_EXTENSION);
                    if (strpos($slider['name']['import-file'], 'sliders_unzip_to_import') !== false) {
                        Notification::error(sprintf(n2_('You have to unzip your %1$s file to find the importable *.ss3 files!'), $slider['name']['import-file']));
                        $this->response->error();
                    } else if ($extension != 'ss3') {
                        Notification::error(n2_('Only *.ss3 files can be uploaded!'));
                        $this->response->error();
                    } else {
                        Notification::error(n2_('Import error!'));
                        $this->response->error();
                    }
                    $this->response->redirect($this->getUrlImport());
                }
            } else {
                Notification::error(n2_('The imported file is not readable!'));
                $this->response->error();
            }

        }
    
    }
}