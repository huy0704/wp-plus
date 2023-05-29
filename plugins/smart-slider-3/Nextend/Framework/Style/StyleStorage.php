<?php

namespace Nextend\Framework\Style;

use Nextend\Framework\Pattern\SingletonTrait;
use Nextend\Framework\Plugin;

class StyleStorage {

    use SingletonTrait;

    private $sets = array();

    private $styles = array();

    private $stylesBySet = array();

    private $stylesById = array();

    protected function init() {


        Plugin::addAction('systemstyleset', array(
            $this,
            'styleSet'
        ));
        Plugin::addAction('systemstyle', array(
            $this,
            'styles'
        ));
        Plugin::addAction('style', array(
            $this,
            'style'
        ));
    }

    private function load() {
        static $loaded;
        if (!$loaded) {
            Plugin::doAction('styleStorage', array(
                &$this->sets,
                &$this->styles
            ));

            for ($i = 0; $i < count($this->styles); $i++) {
                if (!isset($this->stylesBySet[$this->styles[$i]['referencekey']])) {
                    $this->stylesBySet[$this->styles[$i]['referencekey']] = array();
                }
                $this->stylesBySet[$this->styles[$i]['referencekey']][] = &$this->styles[$i];
                $this->stylesById[$this->styles[$i]['id']]              = &$this->styles[$i];
            }
            $loaded = true;
        }
    }

    public function styleSet($referenceKey, &$sets) {

        $this->load();

        for ($i = count($this->sets) - 1; $i >= 0; $i--) {
            $this->sets[$i]['isSystem'] = 1;
            $this->sets[$i]['editable'] = 0;
            array_unshift($sets, $this->sets[$i]);
        }

    }

    public function styles($referenceKey, &$styles) {

        $this->load();

        if (isset($this->stylesBySet[$referenceKey])) {
            $_styles = &$this->stylesBySet[$referenceKey];
            for ($i = count($_styles) - 1; $i >= 0; $i--) {
                $_styles[$i]['isSystem'] = 1;
                $_styles[$i]['editable'] = 0;
                array_unshift($styles, $_styles[$i]);
            }

        }
    }

    public function style($id, &$style) {

        $this->load();

        if (isset($this->stylesById[$id])) {
            $this->stylesById[$id]['isSystem'] = 1;
            $this->stylesById[$id]['editable'] = 0;
            $style                             = $this->stylesById[$id];
        }
    }
}