<?php

namespace Nextend\SmartSlider3\Application\Admin\Settings;

/**
 * @var $this ViewSettingsClearCache
 */
?>
<form id="n2_slider_clear_cache_form" action="<?php echo esc_url($this->getAjaxUrlSettingsClearCache()); ?>" method="post">
    <?php
    $this->renderForm();
    ?>
</form>

<script>
    document.querySelector('.n2_slider_clear_cache').addEventListener('click', function () {
        document.getElementById('n2_slider_clear_cache_form').submit();
    });
</script>