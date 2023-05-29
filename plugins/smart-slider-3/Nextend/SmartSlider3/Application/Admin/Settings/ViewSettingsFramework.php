<?php


namespace Nextend\SmartSlider3\Application\Admin\Settings;


use Nextend\Framework\Form\Container\ContainerTable;
use Nextend\Framework\Form\Element\OnOff;
use Nextend\Framework\Form\Element\Select;
use Nextend\Framework\Form\Element\Text;
use Nextend\Framework\Form\Element\Token;
use Nextend\Framework\Form\Form;
use Nextend\Framework\Settings;

class ViewSettingsFramework extends AbstractViewSettings {

    protected $active = 'framework';

    public function display() {

        parent::display();

        $this->layout->addBreadcrumb(n2_('Framework'), '');

        $this->layout->addContent($this->render('Framework'));

        $this->layout->render();
    }

    public function renderForm() {

        $values = Settings::getAll();

        $form = new Form($this, 'global');
        $form->loadArray($values);


        $table = new ContainerTable($form->getContainer(), 'framework', n2_('Framework'));

        $row1 = $table->createRow('framework-1');

        new Token($row1);
        new OnOff($row1, 'protocol-relative', n2_('Use protocol-relative URL'), 1, array(
            'tipLabel'       => n2_('Use protocol-relative URL'),
            'tipDescription' => n2_('Loads the URLs without a http or https protocol.')
        ));
        new OnOff($row1, 'header-preload', n2_('Header preload'), 0, array(
            'tipLabel'       => n2_('Header preload'),
            'tipDescription' => n2_('If the slider is an important part of your site, tell the browser to preload its files.'),
            'tipLink'        => 'https://smartslider.helpscoutdocs.com/article/1785-framework#header-preload'
        ));

        new OnOff($row1, 'force-english-backend', n2_('English UI'), 0, array(
            'tipLabel'       => n2_('English UI'),
            'tipDescription' => n2_('You can keep using Smart Slider 3 in English, even if your backend isn\'t in English.')
        ));

        new OnOff($row1, 'frontend-accessibility', n2_('Improved frontend accessibility'), 1, array(
            'tipLabel'       => n2_('Improved frontend accessibility'),
            'tipDescription' => n2_('Keeps the clicked element (like a button) in focus unless the focus is changed by clicking away.')
        ));


        $table = new ContainerTable($form->getContainer(), 'javascript', 'JavaScript');

        $row1 = $table->createRow('javascript-1');

        new Text($row1, 'scriptattributes', n2_('Script attributes'), '');

        $table = new ContainerTable($form->getContainer(), 'css', 'CSS');

        $row1 = $table->createRow('css-1');
        new OnOff($row1, 'async-non-primary-css', n2_('Async non-primary CSS'), 0, array(
            'tipLabel'       => n2_('Async non-primary CSS'),
            'tipDescription' => n2_('Google Fonts, icon and lightbox CSS are loaded in a non-blocking way. Disable if you see missing icons, fonts or styles.')
        ));


        $form->render();
    }
}