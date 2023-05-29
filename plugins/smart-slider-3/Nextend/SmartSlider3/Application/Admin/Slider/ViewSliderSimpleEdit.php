<?php

namespace Nextend\SmartSlider3\Application\Admin\Slider;

use Nextend\Framework\Sanitize;
use Nextend\Framework\View\AbstractView;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\Header\BlockHeader;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\TopBarMain\BlockTopBarMain;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button\BlockButton;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button\BlockButtonBack;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button\BlockButtonSave;
use Nextend\SmartSlider3\Application\Admin\Layout\LayoutDefault;
use Nextend\SmartSlider3\Application\Admin\TraitAdminUrl;

class ViewSliderSimpleEdit extends AbstractView {

    use TraitAdminUrl;

    protected $groupID = 0;

    protected $groupTitle = '';

    /**
     * @var array
     */
    protected $slider;

    /**
     * @param int    $groupID
     * @param string $groupTitle
     */
    public function setGroupData($groupID, $groupTitle) {
        $this->groupID    = $groupID;
        $this->groupTitle = $groupTitle;
    }

    /**
     * @param array $slider
     */
    public function setSlider($slider) {
        $this->slider = $slider;
    }

    /**
     * @return array
     */
    public function getSlider() {
        return $this->slider;
    }

    public function display() {

        $this->layout = new LayoutDefault($this);

        if ($this->groupID) {
            $this->layout->addBreadcrumb(Sanitize::esc_html($this->groupTitle), 'ssi_16 ssi_16--folderclosed', $this->getUrlSliderEdit($this->groupID));
        }

        $this->layout->addBreadcrumb(Sanitize::esc_html($this->slider['title']), 'ssi_16 ssi_16--image', $this->getUrlSliderEdit($this->slider['id'], $this->groupID));

        $this->layout->addBreadcrumb(n2_('Simple edit'), 'ssi_16 ssi_16--cog', $this->getUrlSliderSimpleEdit($this->slider['id'], $this->groupID));


        $topBar = new BlockTopBarMain($this);

        $buttonSave = new BlockButtonSave($this);
        $buttonSave->addClass('n2_slider_save');
        $topBar->addPrimaryBlock($buttonSave);


        $buttonBack = new BlockButtonBack($this);
        if ($this->groupID != 0) {
            $buttonBack->setUrl($this->getUrlSliderEdit($this->groupID));
        } else {
            $buttonBack->setUrl($this->getUrlDashboard());
        }
        $buttonBack->addClass('n2_slider_settings_back');
        $topBar->addPrimaryBlock($buttonBack);

        $this->layout->setTopBar($topBar->toHTML());

        $this->displayHeader();

        $this->layout->addContent($this->render('SimpleEdit'));

        $this->layout->render();

    }

    protected function displayHeader() {

        $blockHeader = new BlockHeader($this);
        $blockHeader->setHeading($this->slider['title']);
        $blockHeader->setHeadingAfter('ID: ' . $this->slider['id']);

        $addSlide = new BlockButton($this);
        $addSlide->setGreen();
        $addSlide->setBig();
        $addSlide->setLabel(n2_('Add slide'));
        $addSlide->setUrl($this->getUrlSliderSimpleEditAddSlide($this->slider['id'], $this->groupID));
        $blockHeader->addAction($addSlide->toHTML());

        $this->layout->addContentBlock($blockHeader);
    }
}