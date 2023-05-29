<?php

namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\FloatingMenu;


/**
 * @var BlockFloatingMenu $this
 */
?>
<div <?php $this->renderAttributes(); ?>>
    <?php
    $this->displayButton();

    $contentID = $this->getContentID();
    ?>
    <div <?php if (!empty($contentID)): ?>id="<?php echo esc_attr($this->getContentID()); ?>"<?php endif; ?> class="n2_popover_content n2_floating_menu__items_container">
        <div class="n2_popover_content_exit"></div>
        <div class="n2_popover_content_inner n2_floating_menu__items">
            <?php
            foreach ($this->getMenuItems() as $menuItem) {
                $menuItem->display();
            }
            ?>
        </div>
    </div>
</div>