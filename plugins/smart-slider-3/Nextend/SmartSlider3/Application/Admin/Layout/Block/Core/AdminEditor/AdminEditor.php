<?php

namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\AdminEditor;

use Nextend\Framework\Notification\Notification;
use Nextend\Framework\Plugin;
use Nextend\SmartSlider3\Settings;
use Nextend\SmartSlider3\SmartSlider3Info;

/**
 * @var $this BlockAdminEditor
 */

?>

    <div <?php $this->renderAttributes(); ?>>

        <?php $this->displayEditorOverlay(); ?>

        <div class="n2_admin_editor__content">
            <div class="n2_admin_editor__content_inner" dir="ltr">

                <?php $this->displayContent(); ?>
            </div>
        </div>
        <?php
        Plugin::doAction('afterApplicationContent');
        ?>
    </div>

    <?php

Notification::show();