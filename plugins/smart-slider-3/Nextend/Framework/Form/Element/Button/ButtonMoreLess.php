<?php


namespace Nextend\Framework\Form\Element\Button;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Form\Element\Button;

class ButtonMoreLess extends Button {

    public function __construct($insertAt, $name, $label = '', $parameters = array()) {
        parent::__construct($insertAt, $name, $label, n2_('More'), $parameters);
    }

    protected function fetchElement() {

        $options = array(
            'labelMore' => n2_('More'),
            'labelLess' => n2_('Less')
        );

        if (!empty($this->relatedFields)) {
            $options['relatedFields'] = $this->relatedFields;
        }

        Js::addInline('new _N2.FormElementButtonMoreLess("' . $this->fieldID . '", ' . json_encode($options) . ');');

        return parent::fetchElement();
    }
}