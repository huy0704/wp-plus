<?php

namespace Nextend\Framework\Url;

use Nextend\Framework\Filesystem\Filesystem;

abstract class AbstractPlatformUrl {

    public $uris = array();

    protected $siteUrl;

    /**
     * @var string It can be relative or absolute uri. It must not end with /
     * @example https://asd.com/wordpress
     * @example /wordpress
     */
    protected $_baseuri;

    protected $_currentbase = '';

    protected $scheme = 'http';

    public function getUris() {

        return $this->uris;
    }

    protected function getUriByIndex($i, $protocol = true) {
        if (!$protocol) {
            return preg_replace('/^http:/', '', $this->uris[$i]);
        }

        return $this->uris[$i];
    }

    public function setBaseUri($uri) {
        $this->_baseuri = $uri;
    }

    public function getSiteUri() {
        return $this->siteUrl;
    }

    public function getBaseUri() {

        return $this->_baseuri;
    }

    public function getFullUri() {

        return $this->_baseuri;
    }

    public function pathToUri($path, $protocol = true) {

        $from = array();
        $to   = array();

        $basePath = Filesystem::getBasePath();
        if ($basePath != '/' && $basePath != "\\") {
            $from[] = $basePath;
            $to[]   = '';
        }
        $from[] = DIRECTORY_SEPARATOR;
        $to[]   = '/';

        return ($protocol ? $this->_baseuri : preg_replace('/^http:/', '', $this->_baseuri)) . str_replace($from, $to, str_replace('/', DIRECTORY_SEPARATOR, $path));
    }

    public function ajaxUri($query = '') {

        return $this->_baseuri;
    }

    public function fixrelative($uri) {
        if (substr($uri, 0, 1) == '/' || strpos($uri, '://') !== false) return $uri;

        return $this->_baseuri . $uri;
    }

    public function relativetoabsolute($uri) {

        if (strpos($uri, '://') !== false) return $uri;
        if (!empty($this->_baseuri) && strpos($uri, $this->_baseuri) === 0) {
            $uri = substr($uri, strlen($this->_baseuri));
        }

        return $this->_currentbase . $uri;
    }

    public function addScheme($url) {
        return $this->scheme . ':' . $url;
    }
}