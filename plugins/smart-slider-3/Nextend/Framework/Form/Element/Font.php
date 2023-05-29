<?php

namespace Nextend\Framework\Form\Element;

use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Font\FontManager;
use Nextend\Framework\Font\FontParser;
use Nextend\Framework\View\Html;

class Font extends AbstractFieldHidden {

    protected $mode = '';

    protected $css = '';

    protected $style2 = '';

    protected $preview = '';


    protected function addScript() {

        FontManager::enqueue($this->getForm());

        Js::addInline('new _N2.FormElementFont("' . $this->fieldID . '", {
            mode: "' . $this->mode . '",
            label: "' . $this->label . '",
            style: "' . $this->style . '",
            style2: "' . $this->style2 . '",
            preview: ' . json_encode($this->preview) . '
        });');
    }

    protected function fetchElement() {

        $this->addScript();

        return Html::tag('div', array(
            'class' => 'n2_field_font'
        ), n2_('Font') . parent::fetchElement());
    }

    public function getValue() {

        return FontParser::parse(parent::getValue());
    }

    /**
     * @param string $mode
     */
    public function setMode($mode) {
        $this->mode = $mode;
    }

    /**
     * @param string $css
     */
    public function setCss($css) {
        $this->css = $css;
    }

    /**
     * @param string $style2
     */
    public function setStyle2($style2) {
        $this->style2 = $style2;
    }

    /**
     * @param string $preview
     */
    public function setPreview($preview) {
        $this->preview = $preview;
    }

}