<?php


namespace Nextend\Framework\Request;

use Nextend\Framework\PageFlow;

class Request {

    /**
     * @var Storage
     */
    public static $REQUEST;

    /**
     * @var Storage
     */
    public static $GET;

    /**
     * @var Storage
     */
    public static $POST;

    /**
     * @var Storage
     */
    public static $SERVER;

    /**
     * @var Storage
     */
    public static $COOKIE;

    /**
     * @var Storage
     */
    public static $FILES;

    private static $requestUri;

    public static $isAjax = false;

    public function __construct() {
        self::$REQUEST = new Storage($_REQUEST);
        self::$GET     = new Storage($_GET);
        self::$POST    = new Storage($_POST);
        self::$SERVER  = new Storage($_SERVER);
        self::$COOKIE  = new Storage($_COOKIE);
        self::$FILES   = new Storage($_FILES, true);
    }

    /**
     * @param array|string $url
     * @param integer      $statusCode
     * @param bool         $terminate
     */
    public static function redirect($url, $statusCode = 302, $terminate = true) {

        header('Location: ' . $url, true, $statusCode);
        if ($terminate) {
            PageFlow::exitApplication();
        }
    }
}

new Request();