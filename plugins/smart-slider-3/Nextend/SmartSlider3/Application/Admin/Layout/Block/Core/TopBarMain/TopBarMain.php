<?php

namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\TopBarMain;

/**
 * @var $this BlockTopBarMain
 */
?>
<script>
    _N2.r(['$', 'documentReady'], function () {
        var $ = _N2.$;
        $('#<?php echo esc_html($this->getID()); ?>').css('top', _N2.Window.getTopOffset() + 'px');
    });
</script>
<div id="<?php echo esc_html($this->getID()); ?>" class="n2_admin__top_bar n2_top_bar_main">
    <div class="n2_top_bar_main__primary">
        <?php
        $this->displayPrimary();
        ?>
    </div>
    <div class="n2_top_bar_main__secondary">
        <?php
        $this->displaySecondary();
        ?>
    </div>
</div>