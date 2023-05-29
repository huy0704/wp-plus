<?php

namespace Nextend\Framework\Style\Block\StyleManager;

/**
 * @var BlockStyleManager $this
 */
?>
<div id="n2-lightbox-style" class="n2_fullscreen_editor">
    <div class="n2_fullscreen_editor__overlay"></div>
    <div class="n2_fullscreen_editor__window">
        <div class="n2_fullscreen_editor__nav_bar">
            <div class="n2_fullscreen_editor__nav_bar_label">
                <?php n2_e('Style manager'); ?>
            </div>
            <div class="n2_fullscreen_editor__nav_bar_actions">
                <?php $this->displayTopBar(); ?>
            </div>
        </div>
        <div class="n2_fullscreen_editor__content">
            <div class="n2_fullscreen_editor__content_content n2_container_scrollable">
                <?php $this->displayContent(); ?>
            </div>
        </div>
    </div>
</div>