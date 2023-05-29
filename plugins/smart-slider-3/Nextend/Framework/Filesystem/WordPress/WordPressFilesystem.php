<?php

namespace Nextend\Framework\Filesystem\WordPress;

use Nextend\Framework\Filesystem\AbstractPlatformFilesystem;
use Nextend\Framework\Platform\Platform;
use Nextend\Framework\Url\Url;
use function get_current_blog_id;

class WordPressFilesystem extends AbstractPlatformFilesystem {

    public function init() {

        $this->paths[] = realpath(ABSPATH);

        $this->_basepath = realpath(WP_CONTENT_DIR);

        $this->paths[] = $this->_basepath;

        $this->paths[] = realpath(WP_PLUGIN_DIR);

        $wp_upload_dir = wp_upload_dir();

        /**
         * Amazon S3 storage has s3://my-bucket/uploads upload path. If we found a scheme in the path we will
         * skip the realpath check so it won't fail in the future.
         * @url https://github.com/humanmade/S3-Uploads
         */
        if (!stream_is_local($wp_upload_dir['basedir'])) {
            $uploadPath = $wp_upload_dir['basedir'];
        } else {
            $uploadPath = rtrim(realpath($wp_upload_dir['basedir']), "/\\");
            if (empty($uploadPath)) {
                echo 'Error: Your upload path is not valid or does not exist: ' . esc_html($wp_upload_dir['basedir']);
                $uploadPath = rtrim($wp_upload_dir['basedir'], "/\\");
            } else {
                $this->measurePermission($uploadPath);
            }
        }

        if (strpos($this->_basepath, $uploadPath) !== 0) {
            $this->paths[] = $uploadPath;
        }
    }

    public function getImagesFolder() {
        return Platform::getPublicDirectory();
    }

    public function getWebCachePath() {
        if (is_multisite()) {
            return $this->getBasePath() . NEXTEND_RELATIVE_CACHE_WEB . get_current_blog_id();
        }

        return $this->getBasePath() . NEXTEND_RELATIVE_CACHE_WEB;
    }

    public function getNotWebCachePath() {
        if (is_multisite()) {
            return $this->getBasePath() . NEXTEND_RELATIVE_CACHE_NOTWEB . get_current_blog_id();
        }

        return $this->getBasePath() . NEXTEND_RELATIVE_CACHE_NOTWEB;
    }

    public function absoluteURLToPath($url) {
        $uris = Url::getUris();

        for ($i = count($uris) - 1; $i >= 0; $i--) {
            $uri = $uris[$i];
            if (substr($url, 0, strlen($uri)) == $uri) {

                return str_replace($uri, $this->paths[$i], $url);
            }
        }

        return $url;
    }

    public function tempnam($filename = '', $dir = '') {
        return wp_tempnam($filename, $dir);
    }
}