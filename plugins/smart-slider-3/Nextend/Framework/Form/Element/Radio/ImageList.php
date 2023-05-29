<?php


namespace Nextend\Framework\Form\Element\Radio;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Form\Element\AbstractFieldHidden;
use Nextend\Framework\Url\Url;
use Nextend\Framework\View\Html;

abstract class ImageList extends AbstractFieldHidden {

    protected $hasDisabled = true;

    protected $width = 44;

    protected $column = 5;

    protected $options = array();

    protected function fetchElement() {

        $jsParameters = array(
            'width' => $this->width
        );

        if ($this->hasDisabled) {
            $jsParameters['hasDisabled'] = true;
        }

        $html = Html::openTag("div", array(
            'class' => 'n2_field_image_list',
            'style' => $this->style
        ));

        $html .= parent::fetchElement();
        $html .= '<div class="n2_field_image_list__preview">';

        $html .= '</div>';
        $html .= '<i class="n2_field_image_list__arrow ssi_16 ssi_16--selectarrow"></i>';

        $html .= $this->postHTML();

        $html .= Html::closeTag('div');

        $frontendOptions = array();
        foreach ($this->options as $key => $option) {
            $frontendOptions[$key] = array(
                'url' => Url::pathToUri($option['path'])
            );

            if (!empty($option['label'])) {
                $frontendOptions[$key]['label'] = $option['label'];
            }
        }

        $jsParameters['column']  = min($this->column, count($this->options) + ($this->hasDisabled ? 1 : 0));
        $jsParameters['options'] = $frontendOptions;

        Js::addInline('new _N2.FormElementImageList("' . $this->fieldID . '", ' . json_encode($jsParameters) . ', ' . json_encode($this->relatedFields) . ');');

        return $html;
    }

    /**
     * @param bool $hasDisabled
     */
    public function setHasDisabled($hasDisabled) {
        $this->hasDisabled = $hasDisabled;
    }

    /**
     * @param int $width
     */
    public function setWidth($width) {
        $this->width = $width;
    }

    /**
     * @param int $column
     */
    public function setColumn($column) {
        $this->column = $column;
    }

    protected function postHTML() {
        return '';
    }
}