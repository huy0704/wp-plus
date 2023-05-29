<?php


namespace Nextend\SmartSlider3\Widget;


use Nextend\Framework\View\Html;

class WidgetPlacementSimple extends WidgetPlacement {

    public function add($renderCallback, $stack, $offset = 0) {

        $this->items[] = array(
            'stack'          => $stack,
            'renderCallback' => $renderCallback,
            'offset'         => $offset
        );
    }

    public function render() {

        usort($this->items, function ($a, $b) {
            if ($a['stack'] == $b['stack']) {
                return 0;
            }

            return ($a['stack'] < $b['stack']) ? -1 : 1;
        });

        $out = '';
        foreach ($this->items as $item) {
            $attributes = array();
            if ($item['offset'] != 0) {
                $attributes['style'] = '--widget-offset:' . $item['offset'] . 'px;';
            }
            $out .= call_user_func($item['renderCallback'], $attributes);
        }

        if (!empty($out)) {

            return Html::tag('div', array(
                'class' => 'n2-ss-slider-controls n2-ss-slider-controls-' . $this->name
            ), $out);
        }

        return '';
    }
}