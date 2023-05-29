<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\AdminEmpty;


use Nextend\Framework\Sanitize;
use Nextend\Framework\View\AbstractBlock;
use Nextend\Framework\View\AbstractLayout;
use Nextend\Framework\View\Html;

class BlockAdminEmpty extends AbstractBlock {

    /**
     * @var AbstractLayout
     */
    protected $layout;

    protected $id = 'n2-admin';

    protected $classes = array(
        'n2',
        'n2_admin',
        'n2_admin_ui',
        'n2_admin--empty',
        'fitvidsignore'
    );

    protected $attributes = array();

    /**
     * @param AbstractLayout $layout
     */
    public function setLayout($layout) {
        $this->layout = $layout;
    }

    public function displayContent() {
        $this->layout->displayContent();
    }

    public function display() {

        $this->renderTemplatePart('AdminEmpty');
    }

    public function renderAttributes() {

        echo wp_kses(Html::renderAttributes($this->attributes + array(
                'id'    => $this->id,
                'class' => implode(' ', $this->classes)
            )), Sanitize::$adminTemplateTags);
    }
}