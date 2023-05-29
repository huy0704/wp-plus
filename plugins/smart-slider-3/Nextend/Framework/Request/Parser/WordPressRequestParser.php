<?php

namespace Nextend\Framework\Request\Parser;

class WordPressRequestParser extends AbstractRequestParser {

    private $isSlashed;

    public function __construct() {
        $this->isSlashed = did_action('init') > 0;
    }

    public function parseData($data) {
        if ($this->isSlashed) {
            if (is_array($data)) {
                return $this->stripslashesRecursive($data);
            }

            return stripslashes($data);
        }

        return $data;
    }

    private function stripslashesRecursive($array) {
        foreach ($array as $key => $value) {
            $array[$key] = is_array($value) ? $this->stripslashesRecursive($value) : stripslashes($value);
        }

        return $array;
    }
}