<?php

namespace Nextend\SmartSlider3\Application\Admin\GoPro;


use Nextend\SmartSlider3\Application\Admin\GoPro\BlockAlreadyPurchased\BlockAlreadyPurchased;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Dashboard\DashboardManager\Boxes\BlockDashboardUpgradePro;

/**
 * @var $this ViewGoProIndex
 */
?>

<div class="n2_page_free_go_pro">
    <div class="n2_page_free_go_pro__col">

        <div class="n2_page_free_go_pro__heading">
            <?php n2_e('Ready to go Pro?'); ?>
        </div>

        <div class="n2_page_free_go_pro__subheading">
            <?php n2_e('Supercharge Smart Slider 3 with powerful functionality!'); ?>
        </div>
        <?php
        $upgradePro = new BlockDashboardUpgradePro($this);
        $upgradePro->setHasDismiss(false);
        $upgradePro->setSource('page-go-pro');
        $upgradePro->display();
        ?>
    </div>
    <div class="n2_page_free_go_pro__col">

        <div class="n2_page_free_go_pro__heading">
            <?php n2_e('Already purchased?'); ?>
        </div>

        <div class="n2_page_free_go_pro__subheading">
            <?php n2_e('Get started with the Pro version now!'); ?>
        </div>
        <?php
        $alreadyPurchased = new BlockAlreadyPurchased($this);
        $alreadyPurchased->display();
        ?>
    </div>
</div>
