<?php

namespace Nextend\SmartSlider3\Application\Admin\Sliders;

/**
 * @var $this ViewSlidersGettingStarted
 */

?>
<div class="n2_getting_started">
    <div class="n2_getting_started__heading">
        <?php n2_e('Welcome to Smart Slider 3'); ?>
    </div>
    <div class="n2_getting_started__subheading">
        <?php n2_e('To help you get started, we\'ve put together a super tutorial video that shows you the basic settings.'); ?>
    </div>
    <div class="n2_getting_started__video">
        <div class="n2_getting_started__video_placeholder"></div>
        <iframe width="560" height="315" src="https://www.youtube-nocookie.com/embed/3PPtkRU7D74?rel=0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>
    <div class="n2_getting_started__buttons">
        <div class="n2_getting_started__button_dont_show">
            <a href="<?php echo esc_url($this->getUrlGettingStartedDontShow()); ?>"><?php n2_e('Don\'t show again'); ?></a>
        </div>
        <div class="n2_getting_started__button_dashboard">
            <a href="<?php echo esc_url($this->getUrlDashboard()); ?>"><?php n2_e('Go to dashboard'); ?></a>
        </div>
    </div>
</div>