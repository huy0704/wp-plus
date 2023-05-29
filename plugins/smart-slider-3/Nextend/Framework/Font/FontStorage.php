<?php


namespace Nextend\Framework\Font;

use Nextend\Framework\Pattern\SingletonTrait;
use Nextend\Framework\Plugin;

class FontStorage {

    use SingletonTrait;

    private $sets = array();

    private $fonts = array();

    private $fontsBySet = array();

    private $fontsById = array();

    protected function init() {

        Plugin::addAction('systemfontset', array(
            $this,
            'fontSet'
        ));
        Plugin::addAction('systemfont', array(
            $this,
            'fonts'
        ));
        Plugin::addAction('font', array(
            $this,
            'font'
        ));
    }

    private function load() {
        static $loaded;
        if (!$loaded) {
            Plugin::doAction('fontStorage', array(
                &$this->sets,
                &$this->fonts
            ));

            for ($i = 0; $i < count($this->fonts); $i++) {
                if (!isset($this->fontsBySet[$this->fonts[$i]['referencekey']])) {
                    $this->fontsBySet[$this->fonts[$i]['referencekey']] = array();
                }
                $this->fontsBySet[$this->fonts[$i]['referencekey']][] = &$this->fonts[$i];
                $this->fontsById[$this->fonts[$i]['id']]              = &$this->fonts[$i];
            }
            $loaded = true;
        }
    }

    public function fontSet($referenceKey, &$sets) {

        $this->load();

        for ($i = count($this->sets) - 1; $i >= 0; $i--) {
            $this->sets[$i]['isSystem'] = 1;
            $this->sets[$i]['editable'] = 0;
            array_unshift($sets, $this->sets[$i]);
        }

    }

    public function fonts($referenceKey, &$fonts) {

        $this->load();

        if (isset($this->fontsBySet[$referenceKey])) {
            $_fonts = &$this->fontsBySet[$referenceKey];
            for ($i = count($_fonts) - 1; $i >= 0; $i--) {
                $_fonts[$i]['isSystem'] = 1;
                $_fonts[$i]['editable'] = 0;
                array_unshift($fonts, $_fonts[$i]);
            }

        }
    }

    public function font($id, &$font) {

        $this->load();

        if (isset($this->fontsById[$id])) {
            $this->fontsById[$id]['isSystem'] = 1;
            $this->fontsById[$id]['editable'] = 0;
            $font                             = $this->fontsById[$id];
        }
    }
}