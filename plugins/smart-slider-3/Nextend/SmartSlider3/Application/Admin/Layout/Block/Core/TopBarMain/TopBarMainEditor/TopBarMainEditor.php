<?php

namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\TopBarMain\TopBarMainEditor;

use Nextend\Framework\Sanitize;

/**
 * @var $this BlockTopBarMainEditor
 */
?>
<div id="<?php echo esc_attr($this->getID()); ?>" class="n2_admin__top_bar n2_top_bar_main n2_admin_editor_overlay__top_bar_main">
    <div class="n2_top_bar_main__primary">
        <?php
        $this->displayPrimary();
        ?>
    </div>
    <div class="n2_top_bar_main__logo">
        <a href="<?php echo esc_url($this->getUrlDashboard()); ?>">
            <?php echo wp_kses($this->getApplicationType()
                                    ->getLogo(), Sanitize::$adminTemplateTags); ?>
        </a>
    </div>
    <div class="n2_top_bar_main__secondary">
        <?php
        $this->displaySecondary();
        ?>
    </div>
</div>
