<?php


namespace Nextend\SmartSlider3\Application\Admin\Settings;


use Nextend\Framework\Font\FontSettings;
use Nextend\Framework\Font\FontSources;
use Nextend\Framework\Form\Container\ContainerTable;
use Nextend\Framework\Form\Element\Message\Notice;
use Nextend\Framework\Form\Element\Text;
use Nextend\Framework\Form\Element\Textarea;
use Nextend\Framework\Form\Element\Token;
use Nextend\Framework\Form\Form;

class ViewSettingsFonts extends AbstractViewSettings {

    protected $active = 'fonts';

    public function display() {

        parent::display();

        $this->layout->addBreadcrumb(n2_('Fonts'), '');

        $this->layout->addContent($this->render('Fonts'));

        $this->layout->render();
    }

    public function renderForm() {

        $form = new Form($this, 'fonts');
        new Token($form->getFieldsetHidden());

        $form->loadArray(FontSettings::getData()
                                     ->toArray());
        $form->loadArray(FontSettings::getPluginsData()
                                     ->toArray());

        $table = new ContainerTable($form->getContainer(), 'fonts', n2_('Configuration'));

        $row1 = $table->createRow('fonts-1');

        $instruction = sprintf(n2_('Here you can configure the default font your layers have, and the dropdown list of the fonts. Google Fonts are recognized automatically, but you can use your own custom fonts, too. %1$sLearn how to do that.%2$s'), '<a href="https://smartslider.helpscoutdocs.com/article/1828-using-your-own-fonts" target="_blank">', '</a>');
        new Notice($row1, 'instructions', n2_('Instruction'), $instruction);

        $row2 = $table->createRow('fonts-2');

        new Text($row2, 'default-family', n2_('Default family'), '', array(
            'tipLabel'       => n2_('Default family'),
            'tipDescription' => n2_('This font family is used for the newly added layers.')
        ));


        $row3 = $table->createRow('fonts-1');
        new Textarea($row3, 'preset-families', n2_('Preset font families'), '', array(
            'width'          => 200,
            'height'         => 300,
            'tipLabel'       => n2_('Preset font families'),
            'tipDescription' => n2_('These font families appear in the dropdown list.')
        ));

        $fountSources = FontSources::getFontSources();


        foreach ($fountSources as $fountSource) {

            $table = new ContainerTable($form->getContainer(), $fountSource->getName(), $fountSource->getLabel());

            $fountSource->renderFields($table);
        }

        $form->render();
    }
}