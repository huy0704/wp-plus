<?php

namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Slider\SliderTrash;

use Nextend\Framework\Sanitize;

/**
 * @var BlockSliderTrashBox $this
 */
?>

<div class="n2_slider_manager__box n2_slider_box<?php echo $this->isGroup() ? ' n2_slider_box--group' : ' n2_slider_box--slider'; ?>"
     data-group="<?php echo $this->isGroup() ? '1' : '0'; ?>"
     data-sliderid="<?php echo esc_attr($this->getSliderID()); ?>">

    <?php
    $thumbnailUrl   = esc_url($this->getThumbnail());
    $thumbnailStyle = '';
    if (!empty($thumbnailUrl)) {
        $thumbnailStyle = "background-image: url('" . $thumbnailUrl . "');";
    }
    ?>

    <div class="n2_slider_box__content" style="<?php echo esc_attr($thumbnailStyle); ?>">
        <?php
        if ($this->isThumbnailEmpty()):
            $icon = "ssi_64 ssi_64--image";
            if ($this->isGroup()) {
                $icon = "ssi_64 ssi_64--folder";
            }
            ?>

            <div class="n2_slider_box__icon">
                <div class="n2_slider_box__icon_container">
                    <i class="<?php echo esc_attr($icon); ?>"></i>
                </div>
            </div>

        <?php
        endif;
        ?>

        <div class="n2_slider_box__slider_overlay">
            <a class="n2_slider_box__slider_overlay_restore_button n2_button n2_button--small n2_button--green" href="#">
                <?php
                n2_e('Restore');
                ?>
            </a>
        </div>

        <div class="n2_slider_box__slider_identifiers">
            <div class="n2_slider_box__slider_identifier">
                <?php
                echo '#' . esc_html($this->getSliderID());
                ?>
            </div>
            <?php
            if ($this->isGroup()):
                ?>
                <div class="n2_slider_box__slider_identifier">
                    <?php
                    echo 'GROUP';
                    ?>
                </div>
            <?php
            endif;
            ?>
            <?php
            if ($this->hasSliderAlias()):
                ?>
                <div class="n2_slider_box__slider_identifier">
                    <?php
                    echo esc_html($this->getSliderAlias());
                    ?>
                </div>
            <?php
            endif;
            ?>
        </div>

        <div class="n2_slider_box__slider_actions">
            <a class="n2_slider_box__slider_action_more n2_button_icon n2_button_icon--small n2_button_icon--grey-dark" href="#"><i class="ssi_16 ssi_16--more"></i></a>
        </div>
    </div>

    <div class="n2_slider_box__footer">
        <?php
        if ($this->isGroup()):
            ?>
            <div class="n2_slider_box__footer_icon">
                <i class="ssi_16 ssi_16--folderclosed"></i>
            </div>
        <?php
        endif;
        ?>
        <div class="n2_slider_box__footer_title">
            <?php
            echo esc_html($this->getSliderTitle());
            ?>
        </div>
        <div class="n2_slider_box__footer_children_count">
            <?php
            echo esc_html($this->getChildrenCount());
            ?>
        </div>
    </div>
</div>
