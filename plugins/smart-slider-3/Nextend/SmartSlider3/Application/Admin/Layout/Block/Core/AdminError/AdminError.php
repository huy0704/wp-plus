<?php

namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\AdminError;

/**
 * @var $this BlockAdminError
 */
?>
<div style="margin: 20px;width: 500px;border: 2px solid #1D81F9;background-color: #FFFFFF;border-radius: 5px;padding: 40px 50px;">
    <div style="font-size: 18px;line-height: 28px;font-weight: bold;color: #283F4D;">
        <?php
        echo esc_html($this->getTitle());
        ?>
    </div>
    <div style="font-size: 14px;line-height: 24px;color: #325C77;">
        <?php
        echo esc_html($this->getContent());
        ?>
    </div>
    <?php if ($this->hasUrl()): ?>
        <div style="margin-top: 10px;">
            <a href="<?php echo esc_url($this->getUrl()); ?>" target="_blank" style="font-size: 14px;line-height: 24px;color: #1375E9;text-decoration: none;text-transform: capitalize"><?php n2_e('Read more'); ?></a>
        </div>
    <?php endif; ?>
</div>