<?php


namespace Nextend\SmartSlider3\Application\Admin\Settings;

use Nextend\Framework\Form\Container\ContainerTable;
use Nextend\Framework\Form\Element\Breakpoint;
use Nextend\Framework\Form\Element\Hidden;
use Nextend\Framework\Form\Element\Message\Notice;
use Nextend\Framework\Form\Element\MixedField;
use Nextend\Framework\Form\Element\OnOff;
use Nextend\Framework\Form\Element\Select;
use Nextend\Framework\Form\Element\Text;
use Nextend\Framework\Form\Element\Text\Number;
use Nextend\Framework\Form\Element\Text\TextAutoComplete;
use Nextend\Framework\Form\Element\Textarea;
use Nextend\Framework\Form\Element\Token;
use Nextend\Framework\Form\Form;
use Nextend\Framework\Sanitize;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button\BlockButton;
use Nextend\SmartSlider3\Application\Admin\TraitAdminUrl;
use Nextend\SmartSlider3\Platform\Joomla\JoomlaShim;
use Nextend\SmartSlider3\Settings;

class ViewSettingsGeneral extends AbstractViewSettings {

    use TraitAdminUrl;

    protected $active = 'general';

    const defaults = array(
        'desktop-large-portrait'  => 1440,
        'desktop-large-landscape' => 1440,
        'tablet-large-portrait'   => 1300,
        'tablet-large-landscape'  => 1300,
        'tablet-portrait'         => 1199,
        'tablet-landscape'        => 1199,
        'mobile-large-portrait'   => 900,
        'mobile-large-landscape'  => 1050,
        'mobile-portrait'         => 700,
        'mobile-landscape'        => 900,
    );

    public function display() {

        parent::display();

        $this->layout->addContent($this->render('General'));

        $this->layout->render();
    }

    protected function addHeaderActions() {

        $buttonClearCache = new BlockButton($this);
        $buttonClearCache->setBig();
        $buttonClearCache->setLabel(n2_('Clear cache'));
        $buttonClearCache->setUrl($this->getUrlSettingsClearCache());
        $this->blockHeader->addAction($buttonClearCache->toHTML());

    }

    public function renderForm() {
        $data = Settings::getAll();

        $form = new Form($this, 'settings');
        $form->loadArray($data);

        $table = new ContainerTable($form->getContainer(), 'general', n2_('General settings'));

        $row1 = $table->createRow('general-1');

        new Token($row1);

        new Hidden($row1, 'slidersOrder2', '');

        new Hidden($row1, 'slidersOrder2Direction', '');

        new OnOff($row1, 'autoupdatecheck', n2_('Automatic update check'), 1);

        new OnOff($row1, 'slide-as-file', n2_('Alternative save slide'), 0, array(
            'tipLabel'       => n2_('Alternative save slide'),
            'tipDescription' => n2_('If you experience problems during the save this option might solve them.')
        ));
        new OnOff($row1, 'preview-new-window', n2_('Preview in new window'), 0);

        $row3 = $table->createRow('general-3');

        new OnOff($row3, 'youtube-privacy-enhanced', n2_('YouTube and Vimeo privacy enhanced mode'), 0);

        new Number($row3, 'smooth-scroll-speed', n2_('Smooth scroll speed'), 400, array(
            'wide' => 5,
            'unit' => 'ms'
        ));


        $row4 = $table->createRow('general-4');
        new Textarea($row4, 'external-css-files', n2_('Editor - additional CSS files'), '', array(
            'width'          => 300,
            'tipLabel'       => n2_('Editor - additional CSS files'),
            'tipDescription' => n2_('You can call your own CSS files to our backend, for example, to be able to use custom fonts. Write each URL to a new line.'),
            'tipLink'        => 'https://smartslider.helpscoutdocs.com/article/1783-general#editor-additional-css-files'
        ));
        $table        = new ContainerTable($form->getContainer(), 'general-wordpress', n2_('WordPress settings'));
        $rowWordPress = $table->createRow('general-wordpress-1');

        new OnOff($rowWordPress, 'editor-icon', n2_('Show editor icon'), 1);

        new OnOff($rowWordPress, 'wp-adminbar', n2_('Show in admin bar'), 1);

        new OnOff($rowWordPress, 'yoast-sitemap', n2_('YOAST SEO sitemap - add images'), 1);

        new Number($rowWordPress, 'wordpress-widget-areas', n2_('Create widget area'), 1, array(
            'unit'           => n2_('widget area(s)'),
            'wide'           => 4,
            'min'            => 0,
            'tipLabel'       => n2_('Create widget area'),
            'tipDescription' => n2_('Creates new widget areas which you can place to your theme for easy publishing.')
        ));

        $rowWordPress2 = $table->createRow('general-wordpress-2');

        new OnOff($rowWordPress2, 'wp-ajax-iframe-slider', n2_('Use iframe in AJAX calls'), 0, array(
            'tipLabel'       => n2_('Use iframe in AJAX calls'),
            'tipDescription' => n2_('Loads the slider using an iframe when the page is loaded via AJAX to avoid problems.')
        ));

        $table = new ContainerTable($form->getContainer(), 'breakpoints-table', n2_('Breakpoints'));

        $instructionRow = $table->createRow('breakpoints-row-instruction');
        $instructions   = n2_('Breakpoints define the browser width in pixel when the slider switches to a different device.') . ' ' . n2_('At each slider you can override the global breakpoints with local values.');
        new Notice($instructionRow, 'breakpoints-instructions', n2_('Instruction'), $instructions);

        new Text\HiddenText($table->getFieldsetLabel(), 'responsive-screen-width-tablet-portrait', false, self::defaults['tablet-portrait']);
        new Text\HiddenText($table->getFieldsetLabel(), 'responsive-screen-width-tablet-portrait-landscape', false, self::defaults['tablet-landscape']);

        new Text\HiddenText($table->getFieldsetLabel(), 'responsive-screen-width-mobile-portrait', false, self::defaults['mobile-portrait']);
        new Text\HiddenText($table->getFieldsetLabel(), 'responsive-screen-width-mobile-portrait-landscape', false, self::defaults['mobile-landscape']);

        $rowBreakpoints = $table->createRow('breakpoints-row-1');
        new Breakpoint($rowBreakpoints, 'breakpoints', array(
            'tabletportrait-portrait'  => 'settingsresponsive-screen-width-tablet-portrait',
            'tabletportrait-landscape' => 'settingsresponsive-screen-width-tablet-portrait-landscape',
            'mobileportrait-portrait'  => 'settingsresponsive-screen-width-mobile-portrait',
            'mobileportrait-landscape' => 'settingsresponsive-screen-width-mobile-portrait-landscape'
        ));
    

        $table = new ContainerTable($form->getContainer(), 'focus-offset', n2_('Focus offset'));
        $row1  = $table->createRow('focus-offset-1');
        new Notice($row1, 'focus-instructions', n2_('Instruction'), n2_('This option is used at the full page layout to decrease the slider height. The "Scroll to slider" option also uses this option to determine where to scroll the slider.'));

        $row2 = $table->createRow('focus-offset-2');
        $row2HeightOffsetValue = '#wpadminbar';
        new TextAutoComplete($row2, 'responsive-focus-top', n2_('Top'), $row2HeightOffsetValue, array(
            'style'  => 'width:200px;',
            'values' => array($row2HeightOffsetValue)
        ));
        new Text($row2, 'responsive-focus-bottom', n2_('Bottom'), '', array(
            'style' => 'width:200px;'
        ));


        $table = new ContainerTable($form->getContainer(), 'translate-url', n2_('Translate url'));
        $row1  = $table->createRow('translate-url-1');
        new Notice($row1, 'translate-url-instruction', n2_('Instruction'), n2_('You can change the frontend URL our assets are loading from. It can be useful after moving to a new domain.'));

        $row2 = $table->createRow('translate-url-2');

        $translateUrl = new MixedField($row2, 'translate-url', false, '|*|');
        new Text($translateUrl, 'translate-url-1', n2_('From'), '', array(
            'style'          => 'width:200px;',
            'tipLabel'       => n2_('From'),
            'tipDescription' => n2_('The old URL you want to replace. E.g. https://oldsite.com/')
        ));
        new Text($translateUrl, 'translate-url-2', n2_('To'), '', array(
            'style'          => 'width:200px;',
            'tipLabel'       => n2_('To'),
            'tipDescription' => n2_('The new URL you want to use. E.g. https://newsite.com')
        ));

        $form->render();

        echo '<input name="namespace" value="default" type="hidden">';
    }
}