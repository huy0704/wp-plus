<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\Banner;


use Nextend\Framework\View\AbstractBlock;

class BlockBanner extends AbstractBlock {

    protected $id = '';

    protected $image = 'ssi_64 ssi_64--dummy';

    protected $title = '';

    protected $description = '';

    protected $buttonTitle = '';

    protected $buttonHref = '#';

    protected $buttonOnclick = '';

    protected $closeUrl = '';

    public function display() {

        $this->renderTemplatePart('Banner');
    }

    /**
     * @return string
     */
    public function getID() {
        return $this->id;
    }

    /**
     * @param $id
     */
    public function setID($id) {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getImage() {
        return $this->image;
    }

    /**
     * @param $image
     */
    public function setImage($image) {
        $this->image = $image;
    }

    /**
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @param $title
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param $description
     */
    public function setDescription($description) {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getCloseUrl() {
        return $this->closeUrl;
    }

    /**
     * @param $closeUrl
     */
    public function setCloseUrl($closeUrl) {
        $this->closeUrl = $closeUrl;
    }

    /**
     * @return string
     */
    public function getButtonTitle() {
        return $this->buttonTitle;
    }

    /**
     * @return string
     */
    public function getButtonHref() {
        return $this->buttonHref;
    }

    /**
     * @return string
     */
    public function getButtonOnclick() {
        return $this->buttonOnclick;
    }

    /**
     * @param $button
     */
    public function setButton($button) {
        $this->buttonTitle = $button['title'];
        if (isset($button['href'])) {
            $this->buttonHref = $button['href'];
        }
        if (isset($button['onclick'])) {
            $this->buttonOnclick = $button['onclick'];
        }
    }

}