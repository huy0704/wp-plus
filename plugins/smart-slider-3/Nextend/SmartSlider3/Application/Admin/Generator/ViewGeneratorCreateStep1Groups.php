<?php


namespace Nextend\SmartSlider3\Application\Admin\Generator;


use Nextend\Framework\Sanitize;
use Nextend\Framework\View\AbstractView;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\Header\BlockHeader;
use Nextend\SmartSlider3\Application\Admin\Layout\LayoutDefault;
use Nextend\SmartSlider3\Application\Admin\TraitAdminUrl;
use Nextend\SmartSlider3\Generator\AbstractGeneratorGroup;
use Nextend\SmartSlider3\Generator\GeneratorFactory;

class ViewGeneratorCreateStep1Groups extends AbstractView {

    use TraitAdminUrl;

    protected $groupID = 0;

    protected $groupTitle = '';

    /** @var array */
    protected $slider;

    public function display() {

        $this->layout = new LayoutDefault($this);

        if ($this->groupID) {
            $this->layout->addBreadcrumb(Sanitize::esc_html($this->groupTitle), 'ssi_16 ssi_16--folderclosed', $this->getUrlSliderEdit($this->groupID));
        }

        $this->layout->addBreadcrumb(Sanitize::esc_html($this->slider['title']), 'ssi_16 ssi_16--image', $this->getUrlSliderEdit($this->slider['id'], $this->groupID));

        $this->layout->addBreadcrumb(n2_('Add dynamic slides'), '');

        $blockHeader = new BlockHeader($this);
        $blockHeader->setHeading(n2_('Add dynamic slides'));

        $this->layout->addContentBlock($blockHeader);

        $this->layout->addContent($this->render('CreateStep1Groups'));

        $this->layout->render();
    }

    /**
     * @param int    $groupID
     * @param string $groupTitle
     */
    public function setGroupData($groupID, $groupTitle) {
        $this->groupID    = $groupID;
        $this->groupTitle = $groupTitle;
    }

    /**
     * @return array
     */
    public function getSlider() {
        return $this->slider;
    }

    /**
     * @param array $slider
     */
    public function setSlider($slider) {
        $this->slider = $slider;
    }

    /**
     * @return integer
     */
    public function getSliderID() {
        return $this->slider['id'];
    }

    /**
     * @return AbstractGeneratorGroup[]
     */
    public function getGeneratorGroups() {

        return GeneratorFactory::getGenerators();
    }

}