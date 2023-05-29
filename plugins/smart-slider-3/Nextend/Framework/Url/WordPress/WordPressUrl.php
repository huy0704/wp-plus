<?php

namespace Nextend\Framework\Url\WordPress;

use Nextend\Framework\Filesystem\Filesystem;
use Nextend\Framework\Request\Request;
use Nextend\Framework\Url\AbstractPlatformUrl;
use function content_url;
use function wp_upload_dir;

class WordPressUrl extends AbstractPlatformUrl {

    function __construct() {

        $this->siteUrl = site_url();

        $this->uris[] = $this->siteUrl;

        $this->_baseuri = content_url();

        if (strtolower(Request::$SERVER->getCmd('HTTPS', 'off')) != 'off') {
            $this->_baseuri = str_replace('http://', 'https://', $this->_baseuri);
        }

        $this->scheme = parse_url($this->_baseuri, PHP_URL_SCHEME);

        $this->uris[] = $this->_baseuri;

        $this->uris[] = set_url_scheme(plugins_url());


        $wp_upload_dir = wp_upload_dir();
        $uploadUri     = rtrim($wp_upload_dir['baseurl'], "/\\");
        if (strpos($this->_baseuri, $uploadUri) !== 0) {
            if (strtolower(Request::$SERVER->getCmd('HTTPS', 'off')) != 'off') {
                $uploadUri = str_replace('http://', 'https://', $uploadUri);
            }
            $this->uris[] = $uploadUri;
        }
    }

    public function ajaxUri($query = '') {
        return site_url('/wp-admin/admin-ajax.php?action=' . $query);
    }

    public function pathToUri($path, $protocol = true) {
        $paths = Filesystem::getPaths();

        for ($i = count($paths) - 1; $i >= 0; $i--) {
            $_path = $paths[$i];
            if (substr($path, 0, strlen($_path)) == $_path) {

                return $this->getUriByIndex($i, $protocol) . str_replace(array(
                        $_path,
                        DIRECTORY_SEPARATOR
                    ), array(
                        '',
                        '/'
                    ), str_replace('/', DIRECTORY_SEPARATOR, $path));
            }
        }

        if (substr($path, 0, 1) == '/') {
            return $this->getBaseUri() . $path;
        }

        return $path;
    }
}