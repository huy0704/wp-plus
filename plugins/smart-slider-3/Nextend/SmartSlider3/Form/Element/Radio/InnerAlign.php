<?php

namespace Nextend\SmartSlider3\Form\Element\Radio;

use Nextend\Framework\Form\Element\Radio\AbstractRadioIcon;

class InnerAlign extends AbstractRadioIcon {

    protected $hasInherit = true;

    protected function renderOptions() {

        if ($this->hasInherit) {
            $this->options['inherit'] = 'ssi_16 ssi_16--none';
        }

        $this->options = array_merge($this->options, array(
            'left'   => 'ssi_16 ssi_16--textleft',
            'center' => 'ssi_16 ssi_16--textcenter',
            'right'  => 'ssi_16 ssi_16--textright'
        ));

        return parent::renderOptions();
    }

    /**
     * @param bool $hasInherit
     */
    public function setHasInherit($hasInherit) {
        $this->hasInherit = $hasInherit;
    }
}