<?php
namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Slider\SliderManager;

use Nextend\SmartSlider3\Application\Admin\Layout\Block\Slider\SliderBox\BlockSliderBox;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Slider\SliderManager\ActionBar\BlockActionBar;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Slider\SliderManager\Paginator\BlockPaginator;

/**
 * @var BlockSliderManager $this
 */
$groupID          = $this->getGroupID();
$orderBy          = $this->getOrderBy();
$orderByDirection = $this->getOrderByDirection();

$sliders     = $this->getSliders('published');
$sliderCount = $this->getSliderCount('published', true);

$limit           = $this->getPaginationLimit();
$paginationIndex = $this->getPaginationIndex();

?>
<div class="n2_slider_manager" data-groupid="<?php echo esc_attr($groupID); ?>" data-orderby="<?php echo esc_attr($orderBy); ?>" data-orderbydirection="<?php echo esc_attr($orderByDirection); ?>">
    <?php

    $actionBar = new BlockActionBar($this);
    $actionBar->setSliderManager($this);
    $actionBar->display();

    ?>
    <div class="n2_slider_manager__content">

        <div class="n2_slider_manager__box n2_slider_manager__new_slider">
            <i class="n2_slider_manager__new_slider_icon ssi_48 ssi_48--plus"></i>
            <span class="n2_slider_manager__new_slider_label">
                <?php n2_e('New project'); ?>
            </span>
        </div>
        <?php

        foreach ($sliders as $sliderObj) {

            $blockSliderBox = new BlockSliderBox($this);
            $blockSliderBox->setGroupID($groupID);
            $blockSliderBox->setSlider($sliderObj);
            $blockSliderBox->display();
        }
        ?>
        <?php if ($groupID <= 0) { ?>
            <div class="n2_slider_manager__content--empty">
                <div class="n2_slider_manager__content--empty__logo">
                    <i class="ssi_48 ssi_48--bug"></i>
                </div>
                <div class="n2_slider_manager__content--empty__heading">
                    <?php n2_e('Sorry we couldn’t find any matches'); ?>
                </div>
                <div class="n2_slider_manager__content--empty__paragraph">
                    <?php n2_e('Please try searching with another term.'); ?>
                </div>
            </div>
        <?php } ?>

    </div>
    <?php if ($groupID <= 0) { ?>
        <div class="n2_slider_manager__paginator" data-countstart="<?php echo esc_attr($sliderCount); ?>" data-currentstart="<?php echo esc_attr($paginationIndex); ?>" data-limitstart="<?php echo esc_attr($limit); ?>">
            <?php
            $blockPaginator = new BlockPaginator($this);
            $blockPaginator->setSliderManager($this);
            $blockPaginator->setSliderCount($sliderCount);
            $blockPaginator->setPaginationLimit($limit);
            $blockPaginator->display();
            ?>
        </div>
        <div class="n2_slider_manager__search_label">
            <p class="n2_slider_manager__search_label_item n2_slider_manager__search_label_item"><?php echo sprintf(n2_("Showing %s results for %s."), "<span class='n2_slider_manager__search_label_item__counter'>0</span>", "<span class='n2_slider_manager__search_label_item__keyword'></span>") ?></p>
        </div>
    <?php } ?>
</div>
