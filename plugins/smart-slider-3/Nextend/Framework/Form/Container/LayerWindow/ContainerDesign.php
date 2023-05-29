<?php


namespace Nextend\Framework\Form\Container\LayerWindow;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Form\ContainerGeneral;
use Nextend\Framework\Form\ContainerInterface;
use Nextend\Framework\Sanitize;
use Nextend\Framework\View\Html;

class ContainerDesign extends ContainerGeneral {

    public function __construct(ContainerInterface $insertAt, $name) {
        parent::__construct($insertAt, $name);
    }

    public function renderContainer() {

        $id = 'n2_css_' . $this->name;

        echo wp_kses(Html::openTag('div', array(
            'id'    => $id,
            'class' => 'n2_ss_design_' . $this->name
        )), Sanitize::$adminFormTags);

        $element = $this->first;
        while ($element) {
            $element->renderContainer();
            $element = $element->getNext();
        }

        echo wp_kses(Html::closeTag('div'), Sanitize::$basicTags);

        $options = array(
            'ajaxUrl' => $this->getForm()
                              ->createAjaxUrl('css/index')
        );

        Js::addInline('new _N2.BasicCSS(' . json_encode($id) . ', ' . json_encode($options) . ');');
    }
}