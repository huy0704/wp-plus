<?php

namespace Nextend\SmartSlider3\Application\Admin\Sliders;


use Nextend\Framework\Asset\Js\Js;


/**
 * @var ViewSlidersImport $this
 */

JS::addInline('new _N2.SliderImport();');
?>

<form id="n2-ss-form-slider-import" action="<?php echo esc_url($this->getAjaxUrlImport($this->getGroupID())); ?>" method="post">
    <?php
    $this->renderForm();
    ?>
</form>