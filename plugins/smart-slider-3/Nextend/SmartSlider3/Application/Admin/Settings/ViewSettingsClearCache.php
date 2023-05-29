<?php


namespace Nextend\SmartSlider3\Application\Admin\Settings;


use Nextend\Framework\Cache\CacheImage;
use Nextend\Framework\Form\Container\ContainerTable;
use Nextend\Framework\Form\Element\Message\Notice;
use Nextend\Framework\Form\Element\OnOff;
use Nextend\Framework\Form\Element\Token;
use Nextend\Framework\Form\Form;
use Nextend\Framework\View\AbstractView;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\TopBarMain\BlockTopBarMain;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button\BlockButton;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button\BlockButtonBack;
use Nextend\SmartSlider3\Application\Admin\Layout\LayoutDefault;
use Nextend\SmartSlider3\Application\Admin\TraitAdminUrl;

class ViewSettingsClearCache extends AbstractView {

    use TraitAdminUrl;

    /**
     * @var LayoutDefault
     */
    protected $layout;

    public function display() {

        $this->layout = new LayoutDefault($this);

        $this->layout->addBreadcrumb(n2_('Settings'), 'ssi_16 ssi_16--cog', $this->getUrlSettingsDefault());

        $this->layout->addBreadcrumb(n2_('Clear cache'), '', $this->getUrlSettingsClearCache());

        $this->displayTopBar();

        $this->displayHeader();

        $this->layout->render();

    }

    protected function displayTopBar() {

        $topBar = new BlockTopBarMain($this);

        $buttonClearCache = new BlockButton($this);
        $buttonClearCache->addClass('n2_slider_clear_cache');
        $buttonClearCache->setLabel(n2_('Clear cache'));
        $buttonClearCache->setBig();
        $buttonClearCache->setGreen();
        $topBar->addPrimaryBlock($buttonClearCache);

        $buttonBack = new BlockButtonBack($this);
        $buttonBack->setUrl($this->getUrlSettingsDefault());
        $topBar->addPrimaryBlock($buttonBack);

        $this->layout->setTopBar($topBar->toHTML());
    }

    protected function displayHeader() {

        $this->layout->addContent($this->render('ClearCache'));
    }


    public function renderForm() {

        $form = new Form($this, 'clear_cache');

        new Token($form->getFieldsetHidden());

        $settings = new ContainerTable($form->getContainer(), 'clear-cache-options', n2_('Clear cache options'));


        $row1 = $settings->createRow('clear-cache');

        new OnOff($row1, 'delete-image-cache', n2_('Delete resized image cache'), 0);

        $instructions = sprintf(n2_('If enabled the following folder will be %1$spermanently deleted%2$s: %3$s'), '<b>', '</b>', CacheImage::getStorage()
                                                                                                                                           ->getPath('slider/cache', '', 'image'));
        new Notice($row1, 'instructions', n2_('Instruction'), $instructions);

        $form->render();
    }
}