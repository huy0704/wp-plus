<?php


namespace Nextend\Framework;


use Exception;
use JHttp;
use Nextend\Framework\Misc\Base64;
use Nextend\Framework\Misc\HttpClient;
use Nextend\Framework\Notification\Notification;
use Nextend\Framework\Platform\Platform;
use Nextend\Framework\Request\Request;
use Nextend\Framework\Url\Url;

class Api {

    private static $api = 'https://api.nextendweb.com/v1/';

    public static function getApiUrl() {

        return self::$api;
    }

    public static function api($posts, $returnUrl = false) {

        $api = self::getApiUrl();

        $posts_default = array(
            'platform' => Platform::getName()
        );

        $posts = $posts + $posts_default;

        if ($returnUrl) {
            return $api . '?' . http_build_query($posts, '', '&');
        }
        $request = wp_remote_post($api, array(
            'timeout' => 20,
            'body'    => $posts
        ));
        if (is_wp_error($request)) {
            foreach ($request->get_error_messages() as $errorMessage) {
                Notification::error($errorMessage);
            }

            return null;
        } else {
            $data        = wp_remote_retrieve_body($request);
            $headers     = wp_remote_retrieve_headers($request);
            $contentType = $headers['content-type'];
        }
    

        switch ($contentType) {
            case 'text/html; charset=UTF-8':

                Notification::error(sprintf('Unexpected response from the API.<br>Contact us (support@nextendweb.com) with the following log:') . '<br><textarea style="width: 100%;height:200px;font-size:8px;">' . Base64::encode($data) . '</textarea>');

                return array(
                    'status' => 'ERROR_HANDLED'
                );
                break;
            case 'application/json':
                return json_decode($data, true);
        }

        return $data;
    }

    private static function parseHeaders(array $headers, $header = null) {
        $output = array();
        if ('HTTP' === substr($headers[0], 0, 4)) {
            list(, $output['status'], $output['status_text']) = explode(' ', $headers[0]);
            unset($headers[0]);
        }
        foreach ($headers as $v) {
            $h = preg_split('/:\s*/', $v);
            if (count($h) >= 2) {
                $output[strtolower($h[0])] = $h[1];
            }
        }
        if (null !== $header) {
            if (isset($output[strtolower($header)])) {
                return $output[strtolower($header)];
            }

            return null;
        }

        return $output;
    }
}