<?php


namespace Nextend\SmartSlider3\Application\Admin\Help;


use Nextend\Framework\Api;
use Nextend\Framework\Model\StorageSectionManager;
use Nextend\Framework\Notification\Notification;
use Nextend\SmartSlider3\Application\Admin\AbstractControllerAdmin;
use WP_HTTP_Proxy;

class ControllerHelp extends AbstractControllerAdmin {

    public function actionIndex() {

        $view = new ViewHelpIndex($this);
        $view->display();

    }

    public function actionBrowserIncompatible() {

        $view = new ViewHelpBrowserIncompatible($this);
        $view->display();
    }

    public function actionTestApi() {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, Api::getApiUrl());

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $errorFile = dirname(__FILE__) . '/curl_error.txt';
        $out       = fopen($errorFile, "w");
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_STDERR, $out);
        $proxy = new WP_HTTP_Proxy();

        if ($proxy->is_enabled() && $proxy->send_through_proxy(Api::getApiUrl())) {


            curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);

            curl_setopt($ch, CURLOPT_PROXY, $proxy->host());

            curl_setopt($ch, CURLOPT_PROXYPORT, $proxy->port());


            if ($proxy->use_authentication()) {

                curl_setopt($ch, CURLOPT_PROXYAUTH, CURLAUTH_ANY);

                curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxy->authentication());
            }
        }
    

        $output = curl_exec($ch);

        curl_close($ch);
        fclose($out);
        $log   = array("API Connection Test");
        $log[] = htmlspecialchars(file_get_contents($errorFile));
        unlink($errorFile);

        if (!empty($output)) {
            $log[] = "RESPONSE: " . htmlspecialchars($output);
        }

        if (strpos($output, 'ACTION_MISSING') === false) {
            Notification::error(sprintf(n2_('Unable to connect to the API (%1$s). %2$s See %3$sDebug Information%4$s for more details!'), Api::getApiUrl(), '<br>', '<b>', '</b>'));
        } else {
            Notification::notice(n2_('Successful connection with the API.'));
        }

        $log[] = '------------------------------------------';
        $log[] = '';

        StorageSectionManager::getStorage('smartslider')
                             ->set('log', 'api', json_encode($log));

        $this->redirect($this->getUrlHelp());

    }
}