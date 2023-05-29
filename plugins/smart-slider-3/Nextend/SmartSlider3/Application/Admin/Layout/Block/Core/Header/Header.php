<?php

namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\Header;

use Nextend\Framework\Sanitize;

/**
 * @var $this BlockHeader
 */
?>
<div class="n2_header<?php echo $this->hasMenuItems() ? ' n2_header--has-menu-items' : ''; ?>">
    <div class="n2_header__content">
        <div class="n2_header__heading_container">
            <div class="n2_header__heading">
                <div class="n2_header__heading_primary">
                    <?php
                    echo esc_html($this->getHeading());
                    ?>
                </div>
                <?php
                if ($this->hasHeadingAfter()):
                    ?>
                    <div class="n2_header__heading_after">
                        <?php
                        echo esc_html($this->getHeadingAfter());
                        ?>
                    </div>
                <?php
                endif;
                ?>
            </div>
        </div>
        <?php
        if ($this->hasActions()):
            ?>
            <div class="n2_header__actions">
                <?php
                echo wp_kses(implode('', $this->getActions()), Sanitize::$adminTemplateTags);
                ?>
            </div>
        <?php
        endif;
        ?>
    </div>
    <?php
    if ($this->hasMenuItems()):
        ?>
        <div class="n2_header__menu">
            <?php
            foreach ($this->getMenuItems() as $menuItem) {
                $menuItem->display();
            }
            ?>
        </div>
    <?php
    endif;
    ?>
</div>
