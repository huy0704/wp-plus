<?php

namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Slide\SlideManager\AddSlide;

use Nextend\Framework\Platform\Platform;

/**
 * @var $this BlockAddSlide
 */

?>
<div class="n2_slide_manager__add_slide_actions">
    <div class="n2_slide_manager__add_slide_actions_inner">

        <a href="#" class="n2_slide_manager__add_slide_action n2_slide_manager__add_slide_action--image" data-action="image">
            <div class="n2_slide_manager__add_slide_action_icon">
                <i class="ssi_48 ssi_48--image"></i>
            </div>
            <div class="n2_slide_manager__add_slide_action_label"><?php n2_e('Image'); ?></div>
        </a>

        <a href="#" class="n2_slide_manager__add_slide_action n2_slide_manager__add_slide_action--empty-slide" data-action="empty-slide">
            <div class="n2_slide_manager__add_slide_action_icon">
                <i class="ssi_48 ssi_48--empty"></i>
            </div>
            <div class="n2_slide_manager__add_slide_action_label"><?php n2_e('Blank'); ?></div>
        </a>

        <?php
        if (Platform::hasPosts()) :
            ?>
            <a href="#" class="n2_slide_manager__add_slide_action n2_slide_manager__add_slide_action--post" data-action="post">
                <div class="n2_slide_manager__add_slide_action_icon">
                    <i class="ssi_48 ssi_48--post"></i>
                </div>
                <div class="n2_slide_manager__add_slide_action_label"><?php n2_e('Post'); ?></div>
            </a>
        <?php
        endif;
        ?>

        <a href="#" class="n2_slide_manager__add_slide_action n2_slide_manager__add_slide_action--static" data-action="static-overlay">
            <div class="n2_slide_manager__add_slide_action_icon">
                <i class="ssi_48 ssi_48--static"></i>
            </div>
            <div class="n2_slide_manager__add_slide_action_label"><?php n2_e('Static overlay'); ?></div>
        </a>

        <a href="<?php echo esc_url($this->getDynamicSlidesUrl()); ?>" class="n2_slide_manager__add_slide_action n2_slide_manager__add_slide_action--dynamic">
            <div class="n2_slide_manager__add_slide_action_icon">
                <i class="ssi_48 ssi_48--dynamic"></i>
            </div>
            <div class="n2_slide_manager__add_slide_action_label"><?php n2_e('Dynamic slides'); ?></div>
        </a>

    </div>
</div>