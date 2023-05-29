<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Slider\SliderManager;


use Nextend\Framework\View\AbstractBlock;
use Nextend\SmartSlider3\Application\Model\ModelSliders;
use Nextend\SmartSlider3\Settings;

class BlockSliderManager extends AbstractBlock {

    protected $groupID = 0;

    protected $orderBy = 'ordering';

    protected $orderByDirection = 'ASC';

    protected $paginationIndex = 0;

    protected $paginationLimit = 'all';

    public function display() {
        if ($this->groupID <= 0) {
            $this->orderBy          = Settings::get('slidersOrder2', 'ordering');
            $this->orderByDirection = Settings::get('slidersOrder2Direction', 'ASC');
            $this->paginationLimit  = Settings::get('limit', 'all');
        }


        $this->renderTemplatePart('SliderManager');
    }

    /**
     * @return int
     */
    public function getGroupID() {
        return $this->groupID;
    }

    /**
     * @param int $groupID
     */
    public function setGroupID($groupID) {
        $this->groupID = $groupID;
    }

    /**
     * @return int
     */
    public function getPaginationIndex() {
        return $this->paginationIndex;
    }

    /**
     * @param int $index
     */
    public function setPaginationIndex($index) {
        $this->paginationIndex = $index;
    }


    /**
     * @return int
     */

    public function getPaginationLimit() {
        return $this->paginationLimit;
    }

    /**
     * @param string $status
     *
     */
    public function getSliders($status = '*') {
        $slidersModel = new ModelSliders($this);

        $sliders = $slidersModel->getAll($this->groupID, $status, $this->orderBy, $this->orderByDirection, $this->paginationIndex, $this->paginationLimit);
        if ($this->groupID <= 0 && empty($sliders) && $sliderCount = $this->getSliderCount('published', true)) {
            $lastPageIndex         = intval(ceil(($sliderCount - $this->paginationLimit) / $this->paginationLimit));
            $sliders               = $slidersModel->getAll($this->groupID, $status, $this->orderBy, $this->orderByDirection, $lastPageIndex, $this->paginationLimit);
            $this->paginationIndex = $lastPageIndex;
        }

        return $sliders;
    }

    /**
     * @param string $status
     * @param false  $withGroup
     *
     * @return int
     */

    public function getSliderCount($status = '*', $withGroup = false) {
        $slidersModel = new ModelSliders($this);

        return $slidersModel->getSlidersCount($status, $withGroup);
    }

    /**
     * @return string
     */
    public function getOrderBy() {
        return $this->orderBy;
    }

    /**
     * @return string
     */
    public function getOrderByDirection() {
        return $this->orderByDirection;
    }

}