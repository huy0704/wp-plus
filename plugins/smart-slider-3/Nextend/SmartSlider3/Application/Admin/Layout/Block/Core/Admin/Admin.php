<?php

namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\Admin;

use Nextend\Framework\Notification\Notification;
use Nextend\Framework\Plugin;
use Nextend\Framework\Sanitize;
use Nextend\SmartSlider3\Settings;
use Nextend\SmartSlider3\SmartSlider3Info;

/**
 * @var $this BlockAdmin
 */
?>

    <div <?php $this->renderAttributes(); ?>>
        <div class="n2_admin__header">
            <?php echo wp_kses($this->getHeader(), Sanitize::$adminTemplateTags); ?>
        </div>
        <div class="n2_admin__content">
            <?php echo wp_kses($this->getSubNavigation(), Sanitize::$adminTemplateTags); ?>
            <?php $this->displayTopBar(); ?>

            <?php $this->displayContent(); ?>
        </div>
        <?php
        Plugin::doAction('afterApplicationContent');
        ?>
    </div>

    <?php

Notification::show();