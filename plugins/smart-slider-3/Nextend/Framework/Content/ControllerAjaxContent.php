<?php


namespace Nextend\Framework\Content;


use Nextend\Framework\Controller\Admin\AdminAjaxController;
use Nextend\Framework\Request\Request;

class ControllerAjaxContent extends AdminAjaxController {

    public function actionSearchLink() {
        $this->validateToken();

        $keyword = Request::$REQUEST->getVar('keyword', '');
        $this->response->respond(Content::searchLink($keyword));
    }

    public function actionSearchContent() {
        $this->validateToken();

        $keyword = Request::$REQUEST->getVar('keyword', '');
        $this->response->respond(Content::searchContent($keyword));
    }
}