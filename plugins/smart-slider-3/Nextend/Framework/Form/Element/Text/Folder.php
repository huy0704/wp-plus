<?php


namespace Nextend\Framework\Form\Element\Text;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Browse\BrowseManager;
use Nextend\Framework\Form\Element\Text;
use Nextend\Framework\Image\Image;
use Nextend\Framework\View\Html;

class Folder extends Text {

    protected $width = 300;

    protected function addScript() {

        BrowseManager::enqueue($this->getForm());

        Image::initLightbox();

        Js::addInline("new _N2.FormElementFolders('" . $this->fieldID . "' );");
    }

    protected function post() {

        $html = Html::tag('a', array(
            'href'     => '#',
            'class'    => 'n2_field_text__clear',
            'tabindex' => -1
        ), Html::tag('i', array('class' => 'ssi_16 ssi_16--circularremove'), ''));

        $html .= Html::tag('a', array(
            'href'       => '#',
            'class'      => 'n2_field_text__choose',
            'aria-label' => n2_('Choose')
        ), '<i class="ssi_16 ssi_16--plus"></i>');

        return $html;
    }

    public function setWidth($width) {
        $this->width = $width;
    }
}