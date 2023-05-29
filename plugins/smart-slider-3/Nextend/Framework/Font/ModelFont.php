<?php

namespace Nextend\Framework\Font;

use Nextend\Framework\Form\Container\ContainerTable;
use Nextend\Framework\Form\Element\Button;
use Nextend\Framework\Form\Element\Decoration;
use Nextend\Framework\Form\Element\MixedField;
use Nextend\Framework\Form\Element\Radio\TextAlign;
use Nextend\Framework\Form\Element\Select;
use Nextend\Framework\Form\Element\Select\FontWeight;
use Nextend\Framework\Form\Element\Tab;
use Nextend\Framework\Form\Element\Text\Color;
use Nextend\Framework\Form\Element\Text\Family;
use Nextend\Framework\Form\Element\Text\TextAutoComplete;
use Nextend\Framework\Form\Element\Textarea;
use Nextend\Framework\Form\Form;
use Nextend\Framework\Visual\ModelVisual;

class ModelFont extends ModelVisual {

    protected $type = 'font';

    public function renderForm() {

        $form = new Form($this, 'n2-font-editor');

        $table = new ContainerTable($form->getContainer(), 'font', n2_('Font settings'));

        $table->setFieldsetPositionEnd();

        new Button($table->getFieldsetLabel(), 'font-clear-tab', false, n2_('Clear tab'));

        new Tab($table->getFieldsetLabel(), 'font-state');

        $row1 = $table->createRow('font-row-1');
        new Family($row1, 'family', n2_('Family'), 'Arial, Helvetica', array(
            'style' => 'width:150px;'
        ));
        new Color($row1, 'color', n2_('Color'), '000000FF', array(
            'alpha' => true
        ));

        new MixedField\FontSize($row1, 'size', n2_('Size'), '14|*|px');

        new FontWeight($row1, 'weight', n2_('Font weight'), '');
        new Decoration($row1, 'decoration', n2_('Decoration'));
        new TextAutoComplete($row1, 'lineheight', n2_('Line height'), '18px', array(
            'values' => array(
                'normal',
                '1',
                '1.2',
                '1.5',
                '1.8',
                '2'
            ),
            'style'  => 'width:70px;'
        ));
        new TextAlign($row1, 'textalign', n2_('Text align'), 'inherit');

        $row2 = $table->createRow('font-row-2');

        new TextAutoComplete($row2, 'letterspacing', n2_('Letter spacing'), 'normal', array(
            'values' => array(
                'normal',
                '1px',
                '2px',
                '5px',
                '10px',
                '15px'
            ),
            'style'  => 'width:50px;'
        ));
        new TextAutoComplete($row2, 'wordspacing', n2_('Word spacing'), 'normal', array(
            'values' => array(
                'normal',
                '2px',
                '5px',
                '10px',
                '15px'
            ),
            'style'  => 'width:50px;'
        ));
        new Select($row2, 'texttransform', n2_('Transform'), 'none', array(
            'options' => array(
                'none'       => n2_('None'),
                'capitalize' => n2_('Capitalize'),
                'uppercase'  => n2_('Uppercase'),
                'lowercase'  => n2_('Lowercase')
            )
        ));

        new MixedField\TextShadow($row2, 'tshadow', n2_('Text shadow'), '0|*|0|*|1|*|000000FF');

        new Textarea($row2, 'extracss', 'CSS', '', array(
            'width'  => 200,
            'height' => 26
        ));

        $previewTable = new ContainerTable($form->getContainer(), 'font-preview', n2_('Preview'));

        $previewTable->setFieldsetPositionEnd();

        new Color($previewTable->getFieldsetLabel(), 'preview-background', false, 'ced3d5');

        $form->render();
    }
}