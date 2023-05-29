<?php


namespace Nextend\Framework\Form;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Form\Container\ContainerTab;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\Header\BlockHeader;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\Header\MenuItem;

class FormTabbed extends Form {

    protected $classes = array(
        'n2_form',
        'n2_form_tabbed'
    );

    protected $toggleMode = false;

    protected $sessionID = '';

    /**
     * @param $name
     * @param $label
     *
     * @return ContainerTab
     */
    public function createTab($name, $label) {

        return new ContainerTab($this->container, $name, $label);
    }

    /**
     * @param BlockHeader $blockHeader
     */
    public function addTabsToHeader($blockHeader) {
        $element = $this->container->getFirst();
        while ($element) {
            if ($element instanceof ContainerTab) {

                $tab = new MenuItem($element->getLabel());
                $tab->addClass('n2_form__tab_button');
                $tab->setAttribute('data-related-form', $this->id);
                $tab->setAttribute('data-related-tab', $element->getId());
                $blockHeader->addMenuItem($tab);
            }

            $element = $element->getNext();
        }
    }

    public function render() {
        parent::render();

        Js::addInline('new _N2.FormTabbed("' . $this->id . '", ' . json_encode(array(
                'toggleMode' => $this->toggleMode,
                'sessionID'  => $this->sessionID
            )) . ');');
    }

    /**
     * @param bool $toggleMode
     */
    public function setToggleMode($toggleMode) {
        $this->toggleMode = $toggleMode;
    }

    /**
     * @param string $sessionID
     */
    public function setSessionID($sessionID) {
        $this->sessionID = $sessionID;
    }
}