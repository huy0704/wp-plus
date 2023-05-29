<?php


namespace Nextend\SmartSlider3\Platform\WordPress;


use Exception;
use Nextend\Framework\PageFlow;
use Nextend\Framework\Request\Request;
use Nextend\SmartSlider3\Application\ApplicationSmartSlider3;

class WordPressFrontend {

    public function __construct() {

        add_action('init', array(
            $this,
            'preRender'
        ), 1000000);
    }

    public function preRender() {
        if (Request::$GET->getInt('n2prerender') && Request::$GET->getCmd('n2app') !== '') {
            if (current_user_can('smartslider') || current_user_can('edit_posts') || current_user_can('edit_pages') || (Request::$GET->getCmd('h') === sha1(NONCE_SALT . date('Y-m-d') || Request::$GET->getCmd('h') === sha1(NONCE_SALT . date('Y-m-d', time() - 60 * 60 * 24))))) {
                try {

                    $application = ApplicationSmartSlider3::getInstance();

                    $applicationType = $application->getApplicationTypeFrontend();

                    $applicationType->process('PreRender' . Request::$GET->getCmd('n2controller'), Request::$GET->getCmd('n2action'));

                    PageFlow::exitApplication();
                } catch (Exception $e) {
                    exit;
                }
            } else if (Request::$GET->getInt('sliderid') !== 0 && Request::$GET->getCmd('hash') !== null && md5(Request::$GET->getInt('sliderid') . NONCE_SALT) == Request::$GET->getCmd('hash')) {
                try {
                    $application = ApplicationSmartSlider3::getInstance();

                    $applicationType = $application->getApplicationTypeFrontend();

                    $applicationType->process('PreRenderSlider', 'iframe');

                    PageFlow::exitApplication();
                } catch (Exception $e) {
                    exit;
                }
            }
        }
    }
}