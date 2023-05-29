<a style="margin-bottom:10px;" href="#" onclick="NextendSmartSliderSelectModal(jQuery(this).siblings('input')); return false;" class="button button-primary" title="Select slider">
    Select slider
</a>

<input type="text" name="{{ param.param_name }}" id="{{ param.param_name }}" value="{{ option_value }}">
<?php

use Nextend\SmartSlider3\Platform\WordPress\HelperTinyMCE;

HelperTinyMCE::getInstance()
             ->addForcedFrontend();
?>
