<?php


namespace Nextend\SmartSlider3\BackgroundAnimation;


use Nextend\Framework\Pattern\SingletonTrait;
use Nextend\Framework\Plugin;

class BackgroundAnimationStorage {

    use SingletonTrait;

    private $sets = array();

    private $animation = array();

    private $animationBySet = array();

    private $animationById = array();

    protected function init() {
        Plugin::addAction('smartsliderbackgroundanimationset', array(
            $this,
            'animationSet'
        ));
        Plugin::addAction('smartsliderbackgroundanimation', array(
            $this,
            'animations'
        ));
        Plugin::addAction('backgroundanimation', array(
            $this,
            'animation'
        ));
    }

    private function load() {
        static $loaded;
        if (!$loaded) {
            Plugin::doAction('backgroundAnimationStorage', array(
                &$this->sets,
                &$this->animation
            ));

            for ($i = 0; $i < count($this->animation); $i++) {
                if (!isset($this->animationBySet[$this->animation[$i]['referencekey']])) {
                    $this->animationBySet[$this->animation[$i]['referencekey']] = array();
                }
                $this->animationBySet[$this->animation[$i]['referencekey']][] = &$this->animation[$i];
                $this->animationById[$this->animation[$i]['id']]              = &$this->animation[$i];
            }
            $loaded = true;
        }
    }

    public function animationSet($referenceKey, &$sets) {
        $this->load();

        for ($i = count($this->sets) - 1; $i >= 0; $i--) {
            $this->sets[$i]['isSystem'] = 1;
            $this->sets[$i]['editable'] = 0;
            array_unshift($sets, $this->sets[$i]);
        }

    }

    public function animations($referenceKey, &$animation) {
        $this->load();
        if (isset($this->animationBySet[$referenceKey])) {
            $_animation = &$this->animationBySet[$referenceKey];
            for ($i = count($_animation) - 1; $i >= 0; $i--) {
                $_animation[$i]['isSystem'] = 1;
                $_animation[$i]['editable'] = 0;
                array_unshift($animation, $_animation[$i]);
            }

        }
    }

    public function animation($id, &$animation) {
        $this->load();
        if (isset($this->animationById[$id])) {
            $this->animationById[$id]['isSystem'] = 1;
            $this->animationById[$id]['editable'] = 0;
            $animation                            = $this->animationById[$id];
        }
    }
}