<?php

namespace Nextend\Framework\Form\Element;

use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\ResourceTranslator\ResourceTranslator;
use Nextend\Framework\Style\StyleManager;
use Nextend\Framework\Style\StyleParser;
use Nextend\Framework\View\Html;

class Style extends AbstractFieldHidden {

    protected $mode = '';

    protected $font = '';

    protected $font2 = '';

    protected $style2 = '';

    protected $preview = '';

    protected $css = '';

    protected function addScript() {

        StyleManager::enqueue($this->getForm());

        $preview = preg_replace_callback('/url\(\'(.*?)\'\)/', array(
            $this,
            'fixPreviewImages'
        ), $this->preview);

        Js::addInline('new _N2.FormElementStyle("' . $this->fieldID . '", {
            mode: "' . $this->mode . '",
            label: "' . $this->label . '",
            font: "' . $this->font . '",
            font2: "' . $this->font2 . '",
            style2: "' . $this->style2 . '",
            preview: ' . json_encode($preview) . '
        });');
    }

    protected function fetchElement() {

        $this->addScript();

        return Html::tag('div', array(
            'class' => 'n2_field_style'
        ), n2_('Style') . parent::fetchElement());
    }

    public function fixPreviewImages($matches) {
        return "url(" . ResourceTranslator::toUrl($matches[1]) . ")";
    }

    public function getValue() {

        return StyleParser::parse(parent::getValue());
    }

    /**
     * @param string $mode
     */
    public function setMode($mode) {
        $this->mode = $mode;
    }

    /**
     * @param string $font
     */
    public function setFont($font) {
        $this->font = $font;
    }

    /**
     * @param string $font2
     */
    public function setFont2($font2) {
        $this->font2 = $font2;
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

    /**
     * @param string $css
     */
    public function setCss($css) {
        $this->css = $css;
    }
}