<?php


namespace Nextend\SmartSlider3\Form\Element;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Form\Element\AbstractFieldHidden;
use Nextend\Framework\View\Html;

class Columns extends AbstractFieldHidden {

    protected $hasTooltip = false;

    public function __construct($insertAt, $name = '', $default = '', $parameters = array()) {
        parent::__construct($insertAt, $name, false, $default, $parameters);
    }

    protected function fetchElement() {

        Js::addInline('new _N2.FormElementColumns("' . $this->fieldID . '");');

        return Html::tag('div', array(
            'class' => 'n2_field_columns'
        ), Html::tag('div', array(
                'class' => 'n2_field_columns__content'
            ), '') . Html::tag('div', array(
                'class'      => 'n2_field_columns__add',
                'data-n2tip' => n2_('Add column')
            ), '<div class="ssi_16 ssi_16--plus"></div>') . parent::fetchElement());
    }
}