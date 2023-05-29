<?php

namespace Nextend\Framework\Form\Element\Hidden;

use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Font\FontManager;
use Nextend\Framework\Form\Element\AbstractFieldHidden;

class HiddenFont extends AbstractFieldHidden {

    protected $rowClass = 'n2_form_element--hidden';

    protected $mode = '';

    protected function fetchElement() {

        FontManager::enqueue($this->getForm());

        Js::addInline('new _N2.FormElementFontHidden("' . $this->fieldID . '", {
            mode: "' . $this->mode . '",
            label: "' . $this->label . '"
        });');

        return parent::fetchElement();
    }

    /**
     * @param string $mode
     */
    public function setMode($mode) {
        $this->mode = $mode;
    }
}