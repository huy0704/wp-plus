<?php

namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Slide\EditorOverlay;

/**
 * @var $this BlockEditorOverlay
 */
?>

<div class="n2_admin_editor_overlay">
    <div class="n2_admin_editor_overlay__top">
        <?php $this->displayTopBar(); ?>
    </div>

    <div class="n2_admin_editor_overlay__middle">
        <?php $this->displayBlockAddLayer(); ?>
        <div class="n2_admin_editor_overlay__middle_center">
            <div class="n2_ruler_corner"></div>
            <div class="n2_ruler n2_ruler--vertical">
                <div class="n2_ruler__inner">
                </div>
            </div>
            <div class="n2_ruler n2_ruler--horizontal">
                <div class="n2_ruler__inner">
                </div>
            </div>
        </div>
    </div>

    <?php $this->displayBlockLayerWindow(); ?>

    <?php $this->displaySlideManager(); ?>

</div>
