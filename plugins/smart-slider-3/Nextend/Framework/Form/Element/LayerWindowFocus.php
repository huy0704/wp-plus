<?php


namespace Nextend\Framework\Form\Element;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Form\AbstractField;

class LayerWindowFocus extends AbstractField {

    /**
     * @var AbstractField
     */
    protected $fieldImage;

    /**
     * @var AbstractField
     */
    protected $fieldFocusX;

    /**
     * @var AbstractField
     */
    protected $fieldFocusY;


    /**
     * LayerWindowFocus constructor.
     *
     * @param               $insertAt
     * @param               $name
     * @param               $label
     * @param array         $parameters
     */
    public function __construct($insertAt, $name, $label, $parameters = array()) {

        parent::__construct($insertAt, $name, $label, '', $parameters);
    }

    /**
     * @param AbstractField $fieldImage
     * @param AbstractField $fieldFocusX
     * @param AbstractField $fieldFocusY
     */
    public function setFields($fieldImage, $fieldFocusX, $fieldFocusY) {

        $this->fieldImage  = $fieldImage;
        $this->fieldFocusX = $fieldFocusX;
        $this->fieldFocusY = $fieldFocusY;
    }

    protected function fetchElement() {

        Js::addInline('new _N2.FormElementLayerWindowFocus("' . $this->fieldID . '", ' . json_encode(array(
                'image'  => $this->fieldImage->getID(),
                'focusX' => $this->fieldFocusX->getID(),
                'focusY' => $this->fieldFocusY->getID(),
            )) . ');');

        return '<div id="' . $this->fieldID . '" class="n2_field_layer_window_focus" style="width:314px;"><img class="n2_field_layer_window_focus__image" alt="Error"></div>';
    }

}