<?php

namespace Nextend\SmartSlider3\Form\Element\Radio;

use Nextend\Framework\Form\Element\Radio\AbstractRadioIcon;

class HorizontalAlign extends AbstractRadioIcon {

    protected $inherit = false;

    protected $options = array(
        'left'   => 'ssi_16 ssi_16--horizontalleft',
        'center' => 'ssi_16 ssi_16--horizontalcenter',
        'right'  => 'ssi_16 ssi_16--horizontalright'
    );

    public function __construct($insertAt, $name = '', $label = '', $default = '', array $parameters = array()) {
        parent::__construct($insertAt, $name, $label, $default, $parameters);

        if ($this->inherit) {
            $this->options = array(
                    'inherit' => 'ssi_16 ssi_16--none'
                ) + $this->options;
        }
    }

    /**
     * @param bool $inherit
     */
    public function setInherit($inherit) {
        $this->inherit = $inherit;
    }
}