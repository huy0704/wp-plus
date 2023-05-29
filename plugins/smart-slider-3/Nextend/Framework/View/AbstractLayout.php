<?php


namespace Nextend\Framework\View;


use Nextend\Framework\Pattern\GetPathTrait;
use Nextend\Framework\Pattern\MVCHelperTrait;
use Nextend\Framework\Sanitize;

abstract class AbstractLayout {

    use GetPathTrait;
    use MVCHelperTrait;

    /** @var AbstractView */
    protected $view;

    /**
     * @var AbstractBlock[]|string[]|array[]
     */
    protected $contentBlocks = array();

    protected $state = array();

    /**
     * AbstractLayout constructor.
     *
     * @param AbstractView $view
     *
     */
    public function __construct($view) {
        $this->view = $view;

        $this->setMVCHelper($view);

        $this->getApplicationType()
             ->setLayout($this);

        $this->enqueueAssets();
    }

    protected function enqueueAssets() {

        $this->getApplicationType()
             ->enqueueAssets();
    }

    /**
     * @param string $html contains already escaped data
     */
    public function addContent($html) {

        $this->contentBlocks[] = $html;
    }

    /**
     * @param AbstractBlock $block contains already escaped data
     */
    public function addContentBlock($block) {

        $this->contentBlocks[] = $block;
    }

    public function displayContent() {
        foreach ($this->contentBlocks as $content) {
            if (is_string($content)) {
                // PHPCS - Content already escaped
                echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            } else if (is_array($content)) {
                // PHPCS - Content already escaped
                echo call_user_func_array($content[0], $content[1]); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            } else {
                $content->display();
            }
        }
    }

    public function setState($name, $value) {
        $this->state[$name] = $value;
    }

    public abstract function render();
}