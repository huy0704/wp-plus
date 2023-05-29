<?php

namespace Nextend\Framework\ResourceTranslator;

class ResourceIdentifier {

    /**
     * @var string
     */
    protected $rawKeyword = '';

    /**
     * @var string
     */
    protected $keyword = '';

    /**
     * @var string
     */
    protected $path = '';

    /**
     * @var string
     */
    protected $url = '';

    public function __construct($keyword, $path, $url) {

        /**
         * Keyword must start and end with `$` sign
         */
        if (strlen($keyword) == 0) {
            $keyword = '$';
        } else {
            if ($keyword[0] != '$') {
                $keyword = '$' . $keyword;
            }

            if ($keyword[strlen($keyword) - 1] != '$') {
                $keyword .= '$';
            }
        }

        $this->rawKeyword = $keyword;
        $this->keyword    = $keyword . '/';
        $this->path       = rtrim($path, '/\\') . DIRECTORY_SEPARATOR;
        $this->url        = rtrim($url, '/') . '/';
    }

    public function getRawKeyword() {
        return $this->rawKeyword;
    }

    /**
     * @return string
     */
    public function getKeyword() {
        return $this->keyword;
    }

    /**
     * @return string
     */
    public function getPath() {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getUrl() {
        return $this->url;
    }
}