<?php

namespace Nextend\SmartSlider3\Application\Admin\Slider;

use Nextend\Framework\Asset\Js\Js;
use Nextend\SmartSlider3\Settings;

/**
 * @var $this ViewSliderEdit
 */

$slider = $this->getSlider();

JS::addInline('new _N2.SliderEdit(' . json_encode(array(
        'previewInNewWindow' => !!Settings::get('preview-new-window', 0),
        'saveAjaxUrl'        => $this->getAjaxUrlSliderEdit($slider['id']),
        'previewUrl'         => $this->getUrlPreviewSlider($slider['id']),
        'ajaxUrl'            => $this->getAjaxUrlSliderEdit($slider['id']),
        'formData'           => $this->formManager->getData()
    )) . ');');
?>

<form id="n2-ss-edit-slider-form" action="#" method="post">
    <?php
    $this->renderForm();
    ?>
</form>