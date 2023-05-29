<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Generator\GeneratorBox;


use Nextend\Framework\View\AbstractBlock;

class BlockGeneratorBox extends AbstractBlock {


    protected $label = '';

    protected $buttonLink = '';

    protected $buttonLinkTarget = '';

    protected $buttonLabel = '';

    protected $description = '';

    protected $docsLink = '';

    /** @var string */
    protected $imageUrl;

    public function display() {

        $this->renderTemplatePart('GeneratorBox');
    }

    /**
     * @return string
     */
    public function getLabel() {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel($label) {
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description) {
        $this->description = $description;
    }

    /**
     * @param string
     */
    public function getDocsLink() {
        return $this->docsLink;
    }

    /**
     * @param string $link
     */
    public function setDocsLink($link) {
        $this->docsLink = $link;
    }

    /**
     * @return string
     */
    public function getButtonLink() {
        return $this->buttonLink;
    }

    /**
     * @param string $buttonLink
     */
    public function setButtonLink($buttonLink) {
        $this->buttonLink = $buttonLink;
    }

    /**
     * @return string
     */
    public function getButtonLabel() {
        return $this->buttonLabel;
    }

    /**
     * @param string $buttonLabel
     */
    public function setButtonLabel($buttonLabel) {
        $this->buttonLabel = $buttonLabel;
    }

    public function hasButtonLabel() {
        return !empty($this->buttonLabel);
    }

    /**
     * @return string
     */
    public function getButtonLinkTarget() {
        return $this->buttonLinkTarget;
    }

    /**
     * @param string $buttonLinkTarget
     */
    public function setButtonLinkTarget($buttonLinkTarget) {
        $this->buttonLinkTarget = $buttonLinkTarget;
    }

    /**
     * @return string
     */
    public function getImageUrl() {
        return $this->imageUrl;
    }

    /**
     * @param string $imageUrl
     */
    public function setImageUrl($imageUrl) {
        $this->imageUrl = $imageUrl;
    }
}