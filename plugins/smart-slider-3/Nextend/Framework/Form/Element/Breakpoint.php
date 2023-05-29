<?php


namespace Nextend\Framework\Form\Element;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Form\AbstractField;
use Nextend\Framework\Form\ContainerInterface;
use Nextend\Framework\Form\TraitFieldset;
use Nextend\Framework\View\Html;

class Breakpoint extends AbstractField implements ContainerInterface {

    use TraitFieldset;

    protected $fields = array();

    protected $enables = false;

    protected $global = false;

    public function __construct($insertAt, $name = '', $fields = array(), $enables = false, $global = false) {

        $this->fields  = $fields;
        $this->enables = $enables;
        $this->global  = $global;

        parent::__construct($insertAt, $name, false, '');
    }

    public function getLabelClass() {

        return parent::getLabelClass() . ' n2_field--raw';
    }

    protected function fetchElement() {

        $orientation = new Tab($this, $this->name . '-orientation', n2_('Orientation'), 'portrait', array(
            'options' => array(
                'portrait'  => n2_('Portrait'),
                'landscape' => n2_('Landscape')
            )
        ));
        $devices = array(
            array(
                'id'    => 'mobileportrait',
                'icon'  => 'ssi_16 ssi_16--mobileportrait',
                'label' => n2_('Mobile')
            ),
            array(
                'id'    => 'tabletportrait',
                'icon'  => 'ssi_16 ssi_16--tabletportrait',
                'label' => n2_('Tablet')
            ),
            array(
                'id'    => 'desktopportrait',
                'icon'  => 'ssi_16 ssi_16--desktopportrait',
                'label' => n2_('Desktop')
            )
        );
    

        $preHtml = '';
        $element = $this->first;
        while ($element) {
            $preHtml .= $this->decorateElement($element);

            $element = $element->getNext();
        }

        $html = '';

        for ($i = 0; $i < count($devices); $i++) {
            $html .= Html::tag('div', array(
                'data-id' => $devices[$i]['id'],
                'class'   => 'n2_field_breakpoint__device'
            ), '<div class="n2_field_breakpoint__device_enable" data-n2tip="' . $devices[$i]['label'] . '"><i class="' . $devices[$i]['icon'] . '"></i></div>');
        }

        $options = array(
            'orientation' => $orientation->getID(),
            'fields'      => $this->fields,
            'enables'     => $this->enables,
            'global'      => $this->global
        );

        Js::addInline('new _N2.FormElementBreakpoint("' . $this->fieldID . '", ' . json_encode($options) . ');');


        return '<div id="' . $this->getID() . '" class="n2_field_breakpoint"><div class="n2_field_breakpoint__pre_fields">' . $preHtml . '</div><div class="n2_field_breakpoint__breakpoint_container" data-orientation="portrait">' . $html . '</div></div>';
    }

    public function decorateElement($element) {
        return $this->parent->decorateElement($element);
    }

}