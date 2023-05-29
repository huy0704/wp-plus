<?php

namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Slider\SliderPublish;

/**
 * @var $this BlockPublishSlider
 */
?>

<script>

    _N2.r(['$', 'documentReady'], function () {
        var $ = _N2.$;

        $('.n2_ss_slider_publish__option_code')
            .on('click', function (e) {
                var element = e.currentTarget;
                if (document.selection) {
                    var range = body.createTextRange();
                    range.moveToElementText(this);
                    range.select();
                } else if (window.getSelection) {
                    var range = document.createRange();
                    range.selectNode(element);
                    var selection = window.getSelection();
                    selection.removeAllRanges();
                    selection.addRange(range);
                }
                return false;
            });

        document.addEventListener('copy', function (e) {
            if ($(e.target).hasClass('n2_ss_slider_publish__option_code')) {
                try {
                    e.clipboardData.setData('text/plain', window.getSelection().toString());
                    e.clipboardData.setData('text/html', '<div>' + window.getSelection().toString() + '</div>');
                    e.preventDefault();
                } catch (e) {

                }
            }
        });
    });
</script>
