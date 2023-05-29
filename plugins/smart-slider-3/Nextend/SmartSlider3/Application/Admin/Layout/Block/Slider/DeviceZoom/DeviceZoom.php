<?php

namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Slider\DeviceZoom;

/**
 * @var $this BlockDeviceZoom
 */

?>
<div class="n2_device_changer">
    <div class="n2_device_changer__button">
        <i class="ssi_24 ssi_24--desktop"></i>
    </div>
    <div class="n2_device_tester"></div>
</div>
<script>
    _N2.r(['$', 'documentReady'], function () {
        var $ = _N2.$;
        var timeout,
            $el = $('.n2_device_tester_hover')
                .on({
                    mouseenter: function () {
                        if (timeout) {
                            clearTimeout(timeout);
                            timeout = undefined
                        }
                        $el.addClass('n2_device_tester_hover--hover');
                    },
                    mouseleave: function () {
                        timeout = setTimeout(function () {
                            $el.removeClass('n2_device_tester_hover--hover');
                        }, 400);
                    }
                });
    });
</script>