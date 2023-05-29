<?php


namespace Nextend\Framework\Controller\Admin;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Asset\Predefined;
use Nextend\Framework\Controller\AbstractController;

abstract class AbstractAdminController extends AbstractController {

    public function initialize() {
        // Prevent browser from cache on backward button.
        header("Cache-Control: no-store");

        Js::addGlobalInline('window.N2DISABLESCHEDULER=1;');

        parent::initialize();

        Predefined::frontend();
        Predefined::backend();
    }
}