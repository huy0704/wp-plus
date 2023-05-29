<?php


namespace Nextend\Framework\Asset\Css\Less\Formatter;


#[\AllowDynamicProperties]
class Classic {

    public $indentChar = "  ";

    public $break = "\n";
    public $open = " {";
    public $close = "}";
    public $selectorSeparator = ", ";
    public $assignSeparator = ":";

    public $openSingle = " { ";
    public $closeSingle = " }";

    public $disableSingle = false;
    public $breakSelectors = false;

    public $compressColors = false;

    public function __construct() {
        $this->indentLevel = 0;
    }

    public function indentStr($n = 0) {
        return str_repeat($this->indentChar, max($this->indentLevel + $n, 0));
    }

    public function property($name, $value) {
        return $name . $this->assignSeparator . $value . ";";
    }

    protected function isEmpty($block) {
        if (empty($block->lines)) {
            foreach ($block->children as $child) {
                if (!$this->isEmpty($child)) return false;
            }

            return true;
        }

        return false;
    }

    public function block($block) {
        $ret = '';
        if ($this->isEmpty($block)) return $ret;

        $inner = $pre = $this->indentStr();

        $isSingle = !$this->disableSingle && is_null($block->type) && count($block->lines) == 1;

        if (!empty($block->selectors)) {
            $this->indentLevel++;

            if ($this->breakSelectors) {
                $selectorSeparator = $this->selectorSeparator . $this->break . $pre;
            } else {
                $selectorSeparator = $this->selectorSeparator;
            }

            $ret .= $pre . implode($selectorSeparator, $block->selectors);
            if ($isSingle) {
                $ret   .= $this->openSingle;
                $inner = "";
            } else {
                $ret   .= $this->open . $this->break;
                $inner = $this->indentStr();
            }

        }

        if (!empty($block->lines)) {
            $glue = $this->break . $inner;
            $ret  .= $inner . implode($glue, $block->lines);
            if (!$isSingle && !empty($block->children)) {
                $ret .= $this->break;
            }
        }

        foreach ($block->children as $child) {
            $ret .= $this->block($child);
        }

        if (!empty($block->selectors)) {
            if (!$isSingle && empty($block->children)) $ret .= $this->break;

            if ($isSingle) {
                $ret .= $this->closeSingle . $this->break;
            } else {
                $ret .= $pre . $this->close . $this->break;
            }

            $this->indentLevel--;
        }

        return $ret;
    }
}