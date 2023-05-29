<?php

namespace Nextend\Framework\Style;

use Nextend\Framework\Form\Container\ContainerTable;
use Nextend\Framework\Form\Element\Button;
use Nextend\Framework\Form\Element\MarginPadding;
use Nextend\Framework\Form\Element\MixedField;
use Nextend\Framework\Form\Element\Tab;
use Nextend\Framework\Form\Element\Text\Color;
use Nextend\Framework\Form\Element\Text\NumberAutoComplete;
use Nextend\Framework\Form\Element\Textarea;
use Nextend\Framework\Form\Element\Unit;
use Nextend\Framework\Form\Form;
use Nextend\Framework\Visual\ModelVisual;

class ModelStyle extends ModelVisual {

    protected $type = 'style';

    public function renderForm() {

        $form = new Form($this, 'n2-style-editor');

        $table = new ContainerTable($form->getContainer(), 'style', n2_('Style settings'));

        $table->setFieldsetPositionEnd();

        new Button($table->getFieldsetLabel(), 'style-clear-tab', false, n2_('Clear tab'));

        new Tab($table->getFieldsetLabel(), 'style-state');

        $row1 = $table->createRow('style-row-1');

        new Color($row1, 'backgroundcolor', n2_('Background color'), '000000FF', array(
            'alpha' => true
        ));

        new NumberAutoComplete($row1, 'opacity', n2_('Opacity'), '100', array(
            'values' => array(
                0,
                50,
                90,
                100
            ),
            'unit'   => '%',
            'wide'   => 3
        ));

        $padding = new MarginPadding($row1, 'padding', n2_('Padding'), '0|*|0|*|0|*|0|*|px');
        for ($i = 1; $i < 5; $i++) {
            new NumberAutoComplete($padding, 'padding-' . $i, false, '', array(
                'values' => array(
                    0,
                    5,
                    10,
                    20,
                    30
                ),
                'wide'   => 3
            ));
        }

        new Unit($padding, 'padding-5', '', '', array(
            'units' => array(
                'px',
                'em',
                '%'
            )
        ));

        new MixedField\Border($row1, 'border', n2_('Border'), '0|*|solid|*|000000ff');

        new NumberAutoComplete($row1, 'borderradius', n2_('Border radius'), '0', array(
            'values' => array(
                0,
                3,
                5,
                10,
                99
            ),
            'unit'   => 'px',
            'wide'   => 3
        ));


        new MixedField\BoxShadow($row1, 'boxshadow', n2_('Box shadow'), '0|*|0|*|0|*|0|*|000000ff');

        new Textarea($row1, 'extracss', 'CSS', '', array(
            'width'  => 200,
            'height' => 26
        ));

        $previewTable = new ContainerTable($form->getContainer(), 'style-preview', n2_('Preview'));

        $previewTable->setFieldsetPositionEnd();

        new Color($previewTable->getFieldsetLabel(), 'preview-background', false, 'ced3d5');

        $form->render();
    }
}