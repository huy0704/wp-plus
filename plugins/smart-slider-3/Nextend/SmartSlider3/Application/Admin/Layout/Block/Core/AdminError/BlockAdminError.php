<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\AdminError;


use Nextend\Framework\View\AbstractBlock;
use Nextend\Framework\View\AbstractLayout;

class BlockAdminError extends AbstractBlock {

    /**
     * @var AbstractLayout
     */
    protected $layout;

    protected $title, $content, $url = '';


    /**
     * @param AbstractLayout $layout
     */
    public function setLayout($layout) {
        $this->layout = $layout;
    }

    public function setError($title, $content, $url = '') {
        $this->title   = $title;
        $this->content = $content;
        $this->url     = $url;
    }

    public function displayContent() {
        $this->layout->displayContent();
    }

    public function display() {

        $this->renderTemplatePart('AdminError');
    }

    /**
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * @return bool
     */
    public function hasUrl() {
        return !empty($this->url);
    }

    /**
     * @return string
     */
    public function getUrl() {
        return $this->url;
    }
}