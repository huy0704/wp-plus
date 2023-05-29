<?php

namespace Nextend\Framework\Form\Element\Hidden;

use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Form\Element\AbstractFieldHidden;
use Nextend\Framework\Style\StyleManager;

class HiddenStyle extends AbstractFieldHidden {

    protected $rowClass = 'n2_form_element--hidden';

    protected $mode = '';

    protected function fetchElement() {

        StyleManager::enqueue($this->getForm());

        Js::addInline('new _N2.FormElementStyleHidden("' . $this->fieldID . '", {
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