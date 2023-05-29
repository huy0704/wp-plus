<?php


namespace Nextend\Framework\Form\Element;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\View\Html;

class Devices extends AbstractFieldHidden {

    private $values = array();

    protected function fetchElement() {

        $html = Html::tag('div', array(
            'id'    => $this->fieldID,
            'class' => 'n2_field_radio_icon'
        ), $this->generateOptions());

        Js::addInline('new _N2.FormElementDevices("' . $this->fieldID . '", ' . json_encode($this->values) . ');');

        return $html;
    }

    function generateOptions() {
        $options = array(
            'desktop-portrait' => 'ssi_16 ssi_16--desktopportrait',
            'tablet-portrait'  => 'ssi_16 ssi_16--tabletportrait',
            'mobile-portrait'  => 'ssi_16 ssi_16--mobileportrait'
        );
    

        $html = '';
        $i    = 0;
        foreach ($options as $value => $class) {
            $this->values[] = $value;

            $html .= Html::tag('div', array(
                'class' => 'n2_field_radio__option'
            ), Html::tag('i', array(
                    'class' => $class
                )) . Html::tag('input', array(
                    'type' => 'hidden',
                    'id'   => $this->fieldID . '-' . $value
                )));
            $i++;
        }

        return $html;
    }
}