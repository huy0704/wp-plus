<?php


namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button;


class AbstractButtonLabel extends AbstractButton {

    protected $label = '';

    protected $icon = '';

    protected $iconBefore = "";

    protected $iconBeforeClass = "";

    protected function getContent() {
        $content = '';

        if (!empty($this->iconBefore)) {
            $content .= '<i class="' . $this->iconBefore . ' ' . $this->iconBeforeClass . '"></i>';
        }

        $content .= '<span class="' . $this->baseClass . '__label">' . $this->getLabel() . '</span>';

        if (!empty($this->icon)) {
            $content .= '<i class="' . $this->icon . '"></i>';
        }

        return $content;
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
     * @param string $icon
     */
    public function setIcon($icon) {
        $this->icon = $icon;
    }

    /**
     * @param string $icon
     * @param string $extraClass
     */
    public function setIconBefore($icon, $extraClass = "") {
        $this->iconBefore      = $icon;
        $this->iconBeforeClass = $extraClass;
    }

}