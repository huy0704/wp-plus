<?php

namespace Nextend\Framework\Parser\Link;

use Nextend\Framework\Asset\Js\Js;
use Nextend\SmartSlider3\Settings;

class ScrollTo implements ParserInterface {

    public function __construct() {

        Js::addInline('window.n2ScrollSpeed=' . json_encode(intval(Settings::get('smooth-scroll-speed', 400))) . ';');
    }

    public function parse($argument, &$attributes) {

        switch ($argument) {
            case 'top':
                $onclick = 'n2ss.scroll(event, "top");';
                break;
            case 'bottom':
                $onclick = 'n2ss.scroll(event, "bottom");';
                break;
            case 'beforeSlider':
                $onclick = 'n2ss.scroll(event, "before", this.closest(".n2-ss-slider"));';
                break;
            case 'afterSlider':
                $onclick = 'n2ss.scroll(event, "after", this.closest(".n2-ss-slider"));';
                break;
            case 'nextSlider':
                $onclick = 'n2ss.scroll(event, "next", this, ".n2-section-smartslider");';
                break;
            case 'previousSlider':
                $onclick = 'n2ss.scroll(event, "previous", this, ".n2-section-smartslider");';
                break;
            default:
                if (is_numeric($argument)) {
                    $onclick = 'n2ss.scroll(event, "element", "#n2-ss-' . $argument . '");';
                } else {
                    $onclick = 'n2ss.scroll(event, "element", "' . $argument . '");';
                }
                break;
        }
        $attributes['onclick'] = $onclick;

        return '#';
    }
}