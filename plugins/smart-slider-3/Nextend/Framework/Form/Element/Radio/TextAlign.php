<?php

namespace Nextend\Framework\Form\Element\Radio;

class TextAlign extends AbstractRadioIcon {

    protected $options = array(
        'inherit' => 'ssi_16 ssi_16--none',
        'left'    => 'ssi_16 ssi_16--textleft',
        'center'  => 'ssi_16 ssi_16--textcenter',
        'right'   => 'ssi_16 ssi_16--textright',
        'justify' => 'ssi_16 ssi_16--textjustify'
    );

    /**
     * @param $excluded array
     */
    public function setExcludeOptions($excluded) {
        foreach ($excluded as $exclude) {
            if (isset($this->options[$exclude])) {
                unset($this->options[$exclude]);
            }

        }
    }
}