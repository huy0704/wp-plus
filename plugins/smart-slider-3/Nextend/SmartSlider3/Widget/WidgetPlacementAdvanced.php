<?php


namespace Nextend\SmartSlider3\Widget;


use Nextend\Framework\View\Html;

class WidgetPlacementAdvanced extends WidgetPlacement {

    protected $items = array();

    protected $variables = array();

    public function add($renderCallback, $horizontalSide, $horizontalPosition, $horizontalUnit, $verticalSide, $verticalPosition, $verticalUnit) {

        $attributes = array(
            'style' => ''
        );

        $transforms = array();

        if (is_numeric($horizontalPosition)) {
            $attributes['style'] .= $horizontalSide . ':' . $horizontalPosition . $horizontalUnit . ';';
        } else {
            $attributes['style'] .= $horizontalSide . ':0;';

            $transforms[] = 'translateX(' . $this->toCSSCalc($horizontalSide == 'left' ? 1 : -1, $horizontalPosition) . ')';
        }

        if (is_numeric($verticalPosition)) {
            $attributes['style'] .= $verticalSide . ':' . $verticalPosition . $verticalUnit . ';';
        } else {
            $attributes['style'] .= $verticalSide . ':0;';

            $transforms[] = 'translateY(' . $this->toCSSCalc($verticalSide == 'top' ? 1 : -1, $verticalPosition) . ')';
        }

        if (!empty($transforms)) {
            $attributes['style'] .= 'transform:' . implode(' ', $transforms) . ';';
        }

        $this->items[] = array(
            'renderCallback' => $renderCallback,
            'attributes'     => $attributes
        );
    }


    public function render() {

        $out = '';
        foreach ($this->items as $item) {
            $out .= call_user_func($item['renderCallback'], $item['attributes']);
        }

        if (!empty($out)) {


            return Html::tag('div', array(
                'class'          => 'n2-ss-slider-controls n2-ss-slider-controls-' . $this->name,
                'data-variables' => implode(',', array_unique($this->variables))
            ), $out);
        }

        return '';
    }

    private function toCSSCalc($modifier, $expression) {

        // Remove whitespaces
        $expression = preg_replace('/\s+/', '', $expression);

        // take care of minus symbol on single number
        $expression = preg_replace('/([+\-*\/])[\-]/', '$1[minus]', $expression);

        $expression = preg_replace('/[+\-*\/]/', ' $0 ', $expression);

        $expression = str_replace('[minus]', '-1 * ', $expression);

        preg_match_all('/[a-zA-Z][a-zA-Z0-9]*/', $expression, $matches);

        foreach ($matches as $match) {
            if (!empty($match)) {
                $this->variables = array_merge($this->variables, $match);
            }
        }

        $expression = preg_replace('/[a-zA-Z][a-zA-Z0-9]*/', 'var(--$0, 0)', $expression);

        return 'calc(' . $modifier . 'px * (' . $expression . '))';
    }
}