<?php

namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\ContentSidebar;


use Nextend\Framework\Sanitize;

/**
 * @var $this BlockContentSidebar
 */
?>
<div class="n2-admin-content-with-sidebar">
    <div class="n2-admin-content-with-sidebar__sidebar">
        <?php
        echo wp_kses($this->getSidebar(), Sanitize::$adminTemplateTags);
        ?>
    </div>
    <div class="n2-admin-content-with-sidebar__content">
        <?php
        echo wp_kses($this->getContent(), Sanitize::$adminTemplateTags);
        ?>
    </div>
</div>