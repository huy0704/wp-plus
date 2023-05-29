<?php

namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Slider\SliderManager\Paginator;

use Nextend\Framework\View\AbstractBlock;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button\BlockButtonPlain;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button\BlockButtonPlainIcon;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\FloatingMenu\BlockFloatingMenu;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\FloatingMenu\BlockFloatingMenuItem;

class BlockPaginator extends AbstractBlock {

    protected $sliderCount;
    protected $paginationLimit;

    /**
     * @var BlockSliderManager
     */
    protected $sliderManager;

    public function display() {

        $this->renderTemplatePart('Paginator');
    }

    /**
     * @param BlockSliderManager $sliderManager
     */
    public function setSliderManager($sliderManager) {
        $this->sliderManager = $sliderManager;
    }


    public function setSliderCount($sliderCount) {
        $this->sliderCount = $sliderCount;
    }

    public function setPaginationLimit($limit) {
        $this->paginationLimit = $limit;
    }

    private function transformedPaginationLimit() {
        if ($this->paginationLimit === 'all') {
            /*used in calculations*/
            return $this->sliderCount;
        } else {
            return $this->paginationLimit;
        }
    }


    public function displayPaginationButtons() {

        $totalPages = $this->sliderCount ? ceil(($this->sliderCount / $this->transformedPaginationLimit())) : 0;
        $delta      = 2;
        $left       = intval($this->sliderManager->getPaginationIndex()) - $delta;
        $right      = intval($this->sliderManager->getPaginationIndex()) + $delta;

        /*PageList*/
        if ($totalPages > 1) {
            for ($i = 0; $i < $totalPages; $i++) {
                if ($i == 0 || $i == $totalPages - 1 || $i >= $left && $i <= $right) {
                    $blockButton = new BlockButtonPlain($this);
                    $blockButton->setUrl('#');
                    $blockButton->setLabel($i + 1);
                    $blockButton->addAttribute('data-page', $i);
                    $blockButton->setSmall();
                    $blockButton->setTabIndex(-1);
                    $class = 'n2_slider_manager__paginator_item ' . (($i === intval($this->sliderManager->getPaginationIndex())) ? 'n2_slider_manager__paginator_item--active' : '');
                    $blockButton->addAttribute('class', $class);
                    $blockButton->display();
                } else if ($i === $left - 1 || $i === $right + 1) {
                    echo "<div class='n2_slider_manager__paginator_item n2_slider_manager__paginator_item_spacer'>...</div>";
                }
            }
        }

    }

    public function displayPaginationPrevious() {

        $blockButtonPrev = new BlockButtonPlainIcon($this);
        $blockButtonPrev->setUrl('#');
        $blockButtonPrev->setIcon('ssi_16 ssi_16--paginatiorarrow');
        $blockButtonPrev->setSmall();
        $blockButtonPrev->setTabIndex(-1);
        $blockButtonPrev->addAttribute('data-page', 'prev');
        $blockButtonPrev->addAttribute('class', 'n2_slider_manager__paginator_item n2_slider_manager__paginator_item_arrow n2_slider_manager__paginator_item_arrow--prev n2_slider_manager__paginator_item_arrow--disabled');
        $blockButtonPrev->display();
    }

    public function displayPaginationNext() {
        $blockButtonNext = new BlockButtonPlainIcon($this);
        $blockButtonNext->setUrl('#');
        $blockButtonNext->setIcon('ssi_16 ssi_16--paginatiorarrow');
        $blockButtonNext->setSmall();
        $blockButtonNext->setTabIndex(-1);
        $blockButtonNext->addAttribute('data-page', 'next');
        $blockButtonNext->addAttribute('class', 'n2_slider_manager__paginator_item n2_slider_manager__paginator_item_arrow n2_slider_manager__paginator_item_arrow--next n2_slider_manager__paginator_item_arrow--disabled');
        $blockButtonNext->display();
    }

    public function displayPaginationLimiters() {
        $blockLimiter = new BlockFloatingMenu($this);
        $blockButton  = new BlockButtonPlain($this);
        $limitText    = intval($this->paginationLimit) ? $this->paginationLimit : n2_('All');
        $blockButton->setLabel(n2_('Show') . " <span class='limitNumber'>" . $limitText . "</span>");
        $blockButton->setIcon('ssi_16 ssi_16--selectarrow');
        $blockButton->setSmall();
        $blockLimiter->setButton($blockButton);


        $limits = array(
            10,
            25,
            50,
            100
        );

        foreach ($limits as $limit) {
            $limitItem = new BlockFloatingMenuItem($this);
            $limitItem->setLabel($limit);
            $limitItem->setUrl('#');
            $limitItem->addAttribute('data-limit', $limit);
            $limitItem->addClass('n2_floating_menu__item-limiter');
            $limitItem->setIsActive($this->paginationLimit == $limit);
            $blockLimiter->addMenuItem($limitItem);
        }

        $limitAll = new BlockFloatingMenuItem($this);
        $limitAll->setLabel(n2_('All'));
        $limitAll->setUrl('#');
        $limitAll->addAttribute('data-limit', 'all');
        $limitAll->addClass('n2_floating_menu__item-limiter');
        $limitAll->setIsActive($this->paginationLimit == 'all');
        $blockLimiter->addMenuItem($limitAll);


        $blockLimiter->display();
    }

    public function displayPaginationLabel() {


        $actualSliderStart = $this->transformedPaginationLimit() * $this->sliderManager->getPaginationIndex();
        $actualSlidersEnd  = $actualSliderStart + $this->transformedPaginationLimit();
        $allSliders        = $this->sliderCount;

        echo sprintf(n2_("Showing %s to %s of %s projects"), "<span class='n2_slider_manager__paginator_label_item__from'>" . (($actualSliderStart === 0) ? 1 : esc_html($actualSliderStart)) . "</span>", "<span class='n2_slider_manager__paginator_label_item__to' > " . esc_html(($actualSlidersEnd < $this->sliderCount) ? $actualSlidersEnd : $this->sliderCount) . "</span > ", "<span class='n2_slider_manager__paginator_label_item__max' > " . esc_html($allSliders) . "</span > ");
    }

    public function displayNoSlidersLabel() {
        n2_e('No projects to show');
    }

}