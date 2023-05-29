<?php

namespace Nextend\Framework\Misc;

use Nextend\Framework\Notification\Notification;
use Nextend\Framework\Request\Request;

class HttpClient {

    public static function getCacertPath() {
        return dirname(__FILE__) . '/cacert.pem';
    }

    public static function get($url, $options = array()) {
        $options = array_merge(array('user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36'), $options);

        $request = wp_remote_get($url, $options);
        if (is_wp_error($request)) {
            foreach ($request->get_error_messages() as $errorMessage) {
                Notification::error($errorMessage);
            }

            return false;
        } else {
            $data = wp_remote_retrieve_body($request);
        }

        return $data;
    }

    private static function parseHeaders(array $headers, $header = null) {
    }
}