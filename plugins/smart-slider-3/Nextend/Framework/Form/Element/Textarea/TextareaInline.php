<?php


namespace Nextend\Framework\Form\Element\Textarea;


use Nextend\Framework\Form\Element\Textarea;

class TextareaInline extends Textarea {

    protected $width = 200;

    protected $height = 26;

    protected $classes = array(
        'n2_field_textarea',
        'n2_field_textarea--inline'
    );
}