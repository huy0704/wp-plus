<?php

namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\FreeNeedMore;

use Nextend\Framework\ResourceTranslator\ResourceTranslator;
use Nextend\SmartSlider3\SmartSlider3Info;

/**
 * @var $this BlockFreeNeedMore
 */
?>
<div class="n2_free_need_more">
    <div class="n2_free_need_more__logo">
        <img src="<?php echo esc_url(ResourceTranslator::toUrl('$ss3-admin$/images/logo-filled.svg')); ?>" alt="logo">
    </div>
    <div class="n2_free_need_more__title">
        <?php n2_e('Need more?'); ?>
    </div>
    <div class="n2_free_need_more__paragraph">
        <?php n2_e('Unlock all the pro features by upgrading to Smart Slider 3 Pro.'); ?>
    </div>
    <a href="<?php echo esc_url(SmartSlider3Info::decorateExternalUrl('https://smartslider3.com/pricing/', array('utm_source' => $this->getSource()))); ?>" target="_blank" class="n2_free_need_more__button">
        <?php n2_e('Go Pro'); ?>
    </a>
</div>
