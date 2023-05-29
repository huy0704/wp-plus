<?php


namespace Nextend\Framework\Form\Element\Button;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Form\Element\Button;

class ButtonRecordViewer extends Button {

    public function __construct($insertAt, $name = '', $parameters = array()) {
        parent::__construct($insertAt, $name, '', n2_('View records'), $parameters);


        $this->addClass('n2_field_button--blue');
    }

    protected function fetchElement() {

        $ajaxRecordUrl = $this->getForm()
                              ->createAjaxUrl(array(
                                  'generator/recordstable'
                              ));
        Js::addInline('new _N2.FieldRecordViewer(' . json_encode($this->fieldID) . ',' . json_encode($ajaxRecordUrl) . ');');


        return parent::fetchElement();
    }
}