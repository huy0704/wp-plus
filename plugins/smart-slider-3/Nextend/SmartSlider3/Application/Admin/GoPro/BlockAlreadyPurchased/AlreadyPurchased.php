<?php

namespace Nextend\SmartSlider3\Application\Admin\GoPro\BlockAlreadyPurchased;

use Nextend\SmartSlider3\SmartSlider3Info;

/**
 * @var BlockAlreadyPurchased $this
 */
?>
<div class="n2_page_free_go_pro_already_purchased">

    <div class="n2_page_free_go_pro_already_purchased__logo">
        <i class="ssi_48 ssi_48--protect"></i>
    </div>

    <div class="n2_page_free_go_pro_already_purchased__heading">
        <?php n2_e('How to upgrade to Smart Slider 3 Pro?'); ?>
    </div>

    <div class="n2_page_free_go_pro_already_purchased__paragraph">
        <?php echo sprintf(n2_('After making your purchase, %1$slog in to your account%3$s and download the Pro installer. To get started with Smart Slider 3 Pro, simply %2$sinstall it on your website%3$s.'), '<a href="' . esc_url(SmartSlider3Info::decorateExternalUrl('https://secure.nextendweb.com/', array('utm_source' => 'already-purchased'))) . '" target="_blank">', '<a href="https://smartslider.helpscoutdocs.com/category/1696-installation" target="_blank">', '</a>'); ?>
    </div>

    <a href="<?php echo esc_url(SmartSlider3Info::decorateExternalUrl('https://secure.nextendweb.com/', array('utm_source' => 'already-purchased'))); ?>" target="_blank" class="n2_page_free_go_pro_already_purchased__button">
        <?php n2_e('Download Pro'); ?>
    </a>

    <div class="n2_page_free_go_pro_already_purchased__paragraph">
        <?php n2_e('Feel free to remove the Free version, as you no longer need it. Your sliders will stay!'); ?>
    </div>
</div>