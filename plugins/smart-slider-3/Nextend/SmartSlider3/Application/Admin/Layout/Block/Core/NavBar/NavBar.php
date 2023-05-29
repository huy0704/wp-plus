<?php

namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\NavBar;

use Nextend\Framework\Sanitize;

/**
 * @var $this BlockNavBar
 */
?>
<div class="n2_nav_bar">

    <?php $this->displayBreadCrumbs(); ?>

    <div class="n2_nav_bar__logo">
        <a href="<?php echo esc_url($this->getSidebarLink()); ?>" tabindex="-1">
            <?php echo wp_kses($this->getLogo(), Sanitize::$adminTemplateTags); ?>
        </a>
    </div>
    <div class="n2_nav_bar__menu">
        <?php
        foreach ($this->getMenuItems() as $menuItem):
            ?>
            <div class="n2_nav_bar__menuitem<?php echo $menuItem->isActive() ? ' n2_nav_bar__menuitem--active' : ''; ?>"><?php $menuItem->display(); ?></div>
        <?php
        endforeach;
        ?>
    </div>
</div>
