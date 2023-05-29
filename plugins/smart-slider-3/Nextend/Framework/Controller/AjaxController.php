<?php

namespace Nextend\Framework\Controller;

use Nextend\Framework\Form\Form;
use Nextend\Framework\Notification\Notification;
use Nextend\Framework\PageFlow;
use Nextend\Framework\Response\ResponseAjax;

class AjaxController extends AbstractController {

    /** @var ResponseAjax */
    protected $response;

    public function __construct($applicationType) {
        PageFlow::cleanOutputBuffers();

        $this->response = new ResponseAjax($applicationType);
        parent::__construct($applicationType);
    }

    /**
     * @return ResponseAjax
     */
    public function getResponse() {
        return $this->response;
    }

    public function validateToken() {

        if (!Form::checkToken()) {
            Notification::error(n2_('Security token mismatch. Please refresh the page!'));
            $this->response->error();
        }
    }

    public function validatePermission($permission) {

        if (!$this->canDo($permission)) {

            Notification::error(n2_('You are not authorised to view this resource.'));

            $this->response->error();
        }
    }

    public function validateVariable($condition, $property) {

        if (!$condition) {
            Notification::error(sprintf(n2_('Missing parameter: %s'), $property));
            $this->response->error();
        }
    }

    public function validateDatabase($condition, $showError = true) {
        if (!$condition) {
            Notification::error(n2_('Database error'));
            $this->response->error();
        }
    }

    public function redirect($url, $statusCode = 302, $terminate = true) {
        $this->response->redirect($url);
    }

}