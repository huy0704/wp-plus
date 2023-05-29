<?php

namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Generator\GeneratorBox;

/**
 * @var $this BlockGeneratorBox
 */
?>
<div class="n2_slide_generator_box" style="background-image: url('<?php echo esc_url($this->getImageUrl()); ?>');">
    <div class="n2_slide_generator_box__title">
        <div class="n2_slide_generator_box__title_label">
            <div class="n2_slide_generator_box__title_label_inner">
                <?php
                $label = $this->getLabel();
                echo esc_html($label);
                ?>
            </div>
            <i class="ssi_16 ssi_16--info" data-tip-description="<?php echo esc_attr($this->getDescription()); ?>" data-tip-label="<?php echo esc_attr($label); ?>" data-tip-link="<?php echo esc_url($this->getDocsLink()); ?>"></i>
        </div>
        <a href="<?php echo esc_url($this->getButtonLink()); ?>" target="<?php echo esc_attr($this->getButtonLinkTarget()); ?>" class="n2_slide_generator_box__title_button">
            <?php echo esc_html($this->getButtonLabel()); ?>
        </a>
    </div>
</div>