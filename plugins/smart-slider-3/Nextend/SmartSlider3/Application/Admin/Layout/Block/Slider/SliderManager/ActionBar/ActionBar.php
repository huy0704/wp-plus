<?php

namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Slider\SliderManager\ActionBar;

/**
 * @var BlockActionBar $this
 */
?>
<div class="n2_slider_manager__action_bar">
    <div class="n2_slider_manager__action_bar_left">
        <?php

        $this->displayOrderBy();

        $this->displayCreateGroup();

        $this->displayTrash();

        $this->displayBulkActions();

        ?>
    </div>
    <div class="n2_slider_manager__action_bar_right">
        <?php if ($this->sliderManager->getGroupID() == 0) { ?>
            <div class="n2_slider_manager__search">
                <div class="n2_slider_manager__search_icon n2_slider_manager__search_icon--magnifier">
                    <i class="ssi_16 ssi_16--magnifier"></i>
                </div>
                <div class="n2_slider_manager__search_icon n2_slider_manager__search_icon--abort">
                    <i class="ssi_16 ssi_16--circularremove"></i>
                </div>
                <form class="n2_slider_manager__search_form" autocomplete="off">
                    <input type="text" name="kw" class="n2_slider_manager__search_input" value="" placeholder="<?php n2_e('Search Project'); ?>" tabindex="-1">
                </form>
            </div>
        <?php } ?>
    </div>

</div>
