<?php


namespace Nextend\Framework\Form\Element\Text;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Browse\BrowseManager;
use Nextend\Framework\Form\Element\AbstractChooserText;
use Nextend\Framework\Image\Image;
use Nextend\Framework\ResourceTranslator\ResourceTranslator;
use Nextend\Framework\Sanitize;

class FieldImage extends AbstractChooserText {

    protected $attributes = array();

    protected $relatedAlt = '';

    protected $class = ' n2_field_text_image';

    protected function addScript() {

        $options = array();
        if (!empty($this->relatedAlt)) {
            $options['alt'] = $this->relatedAlt;
        }

        Js::addInline("new _N2.FormElementImage('" . $this->fieldID . "', " . json_encode($options) . " );");
    }

    protected function fetchElement() {

        BrowseManager::enqueue($this->getForm());

        $html = parent::fetchElement();

        Image::initLightbox();

        return $html;
    }

    protected function pre() {

        return '<div class="n2_field_text_image__preview" style="' . $this->getImageStyle() . '"></div>';
    }

    protected function getImageStyle() {
        $image = $this->getValue();
        if (empty($image) || $image[0] == '{') {
            return '';
        }

        return 'background-image: url(' . esc_url(ResourceTranslator::toUrl($image)) . ');';
    }

    /**
     * @param string $relatedAlt
     */
    public function setRelatedAlt($relatedAlt) {
        $this->relatedAlt = $relatedAlt;
    }
}