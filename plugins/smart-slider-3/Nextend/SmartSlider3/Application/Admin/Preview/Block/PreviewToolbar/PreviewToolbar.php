<?php


namespace Nextend\SmartSlider3\Application\Admin\Preview\Block\PreviewToolbar;

use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button\BlockButtonPlainIcon;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button\BlockButtonSpacer;

/**
 * @var $this BlockPreviewToolbar
 */

?>
<div class="n2_preview_toolbar">
    <div class="n2_preview_toolbar__size">
        <div class="n2_preview_toolbar__editable n2_preview_toolbar__width">
            1200
        </div>
        <div class="n2_preview_toolbar__x">
            X
        </div>
        <div class="n2_preview_toolbar__editable n2_preview_toolbar__height">
            800
        </div>
    </div>
    <select class="n2_preview_toolbar__scale">
        <option value="25">25%</option>
        <option value="50">50%</option>
        <option value="75">75%</option>
        <option value="100" selected>100%</option>
        <option value="125">125%</option>
        <option value="150">150%</option>
    </select>
    <?php

    $buttonOrientation = new BlockButtonPlainIcon($this);
    $buttonOrientation->addClass('n2_button_preview_orientation');
    $buttonOrientation->addAttribute('data-n2tip', n2_('Toggle orientation'));
    $buttonOrientation->setBig();
    $buttonOrientation->setIcon('ssi_24 ssi_24--orientation');
    $buttonOrientation->display();

    $spacer = new BlockButtonSpacer($this);
    $spacer->setIsVisible(true);
    $spacer->display();

    $buttonReload = new BlockButtonPlainIcon($this);
    $buttonReload->addClass('n2_button_preview_reload');
    $buttonReload->addAttribute('data-n2tip', n2_('Reload preview'));
    $buttonReload->setBig();
    $buttonReload->setIcon('ssi_24 ssi_24--redo');
    $buttonReload->display();

    $buttonFullPreview = new BlockButtonPlainIcon($this);
    $buttonFullPreview->setUrl($this->getUrlPreviewFull($this->getSliderID()));
    $buttonFullPreview->addAttribute('data-n2tip', n2_('Open preview in full'));
    $buttonFullPreview->setTarget('_blank');
    $buttonFullPreview->setBig();
    $buttonFullPreview->setIcon('ssi_24 ssi_24--newwindow');
    $buttonFullPreview->display();
    ?>
</div>
