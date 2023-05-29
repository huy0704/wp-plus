<?php

namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Slider\SliderPublish;

use Nextend\SmartSlider3\Application\Model\ModelSliders;

/**
 * @var $this BlockPublishSlider
 */
$model    = new ModelSliders($this);
$sliderID = $this->getSliderID();
if ($sliderID === 0) {
    $helper   = $model->getByAlias($this->getSliderAlias());
    $sliderID = $helper['id'];
}
$slider = $model->get($sliderID);
?>

<div class="n2_ss_slider_publish">

    <div class="n2_ss_slider_publish__option">
        <div class="n2_ss_slider_publish__option_label"><?php n2_e('Shortcode'); ?></div>

        <div class="n2_ss_slider_publish__option_description"><?php n2_e('Copy and paste this shortcode into your posts or pages:'); ?></div>
        <div class="n2_ss_slider_publish__option_code" data-mode="id" dir="ltr">
            [smartslider3 slider="<?php echo esc_html($sliderID); ?>"]
        </div>
        <?php if (!empty($slider['alias'])): ?>
            <div class="n2_ss_slider_publish__option_code" data-mode="alias" dir="ltr">
                [smartslider3 alias="<?php echo esc_html($slider['alias']); ?>"]
            </div>
        <?php endif; ?>
    </div>

    <div class="n2_ss_slider_publish__option">
        <div class="n2_ss_slider_publish__option_label"><?php n2_e('Pages and Posts'); ?></div>

        <?php
        $pageBuilders = array(
            'Gutenberg',
            'Classic Editor',
            'Elementor',
            'Divi',
            'Beaver Builder',
            'Visual Composer',
            'WPBakery Page Builder'
        );
        ?>
        <div class="n2_ss_slider_publish__option_description"><?php echo sprintf(n2_('Smart Slider 3 has integration with %s.'), esc_html(implode(', ', $pageBuilders))); ?></div>
    </div>

    <div class="n2_ss_slider_publish__option">
        <div class="n2_ss_slider_publish__option_label"><?php n2_e('PHP code'); ?></div>

        <div class="n2_ss_slider_publish__option_description"><?php n2_e('Paste the PHP code into your theme\'s file:'); ?></div>
        <div class="n2_ss_slider_publish__option_code" dir="ltr">
            &lt;?php <br>
            echo do_shortcode('[smartslider3 slider="<?php echo esc_html($sliderID); ?>"]');<br>
            ?&gt;
        </div>
    </div>
</div>