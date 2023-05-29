<?php

namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Slider\SliderManager\Paginator;

/**
 * @var BlockPaginator $this
 */

?>
<div class="n2_slider_manager__paginator_label <?php echo $this->sliderCount === 0 ? "n2_slider_manager__paginator_label--nosliders" : "" ?>">
    <p class="n2_slider_manager__paginator_label_item n2_slider_manager__paginator_label_item--active"><?php $this->displayPaginationLabel(); ?></p>
    <p class="n2_slider_manager__paginator_label_item n2_slider_manager__paginator_label_item--empty"><?php $this->displayNoSlidersLabel(); ?></p>
</div>
<div class=" n2_slider_manager__paginator_buttons">

    <?php
    $this->displayPaginationPrevious();
    ?>
    <div class="n2_slider_manager__paginator_buttons--numbers">
        <?php
        $this->displayPaginationButtons();
        ?>
    </div>
    <?php
    $this->displayPaginationNext();
    ?>
</div>
<div class="n2_slider_manager__paginator_limiter">
    <?php $this->displayPaginationLimiters() ?>
</div>




