<?php


namespace Nextend\SmartSlider3\Form\Element\Radio;


use Nextend\Framework\Form\Element\Radio\AbstractRadioIcon;

class FlexAlign extends AbstractRadioIcon {

    protected $options = array(
        'flex-start'    => 'ssi_16 ssi_16--verticaltop',
        'center'        => 'ssi_16 ssi_16--verticalcenter',
        'flex-end'      => 'ssi_16 ssi_16--verticalbottom',
        'space-between' => 'ssi_16 ssi_16--verticalbetween',
        'space-around'  => 'ssi_16 ssi_16--verticalaround'
    );
}