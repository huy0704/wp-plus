<?php


namespace Nextend\Framework\Asset\Css\Less\Formatter;


class Compressed extends Classic {

    public $disableSingle = true;
    public $open = "{";
    public $selectorSeparator = ",";
    public $assignSeparator = ":";
    public $break = "";
    public $compressColors = true;

    public function indentStr($n = 0) {
        return "";
    }
}