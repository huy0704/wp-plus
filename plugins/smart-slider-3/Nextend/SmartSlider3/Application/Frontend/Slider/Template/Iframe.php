<?php

namespace Nextend\SmartSlider3\Application\Frontend\Slider;

use Nextend\Framework\Asset\AssetManager;
use Nextend\Framework\Request\Request;
use Nextend\SmartSlider3\Settings;
use Nextend\WordPress\OutputBuffer;

/**
 * @var ViewIframe $this
 */

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex">
    <title>Slider</title>
    <style>
        html, body {
            overflow: hidden;
        }

        body * {
            background-attachment: scroll !important;
        }
    </style>
    <?php
    /**
     * In page builder -> editors, we must force sliders to be visible on every device.
     */
    if (Request::$GET->getInt('iseditor')):
        ?>
        <script>
            window.ssOverrideHideOn = {
                desktopLandscape: 0,
                desktopPortrait: 0,
                tabletLandscape: 0,
                tabletPortrait: 0,
                mobileLandscape: 0,
                mobilePortrait: 0
            };
        </script>
    <?php
    endif;
    ?>

    <?php


    $handlers = ob_list_handlers();
    if (!in_array(OutputBuffer::class . '::outputCallback', $handlers)) {
        if (class_exists('\\Nextend\\Framework\\Asset\\AssetManager', false)) {

            // PHPCS - Content already escaped
            echo AssetManager::getCSS(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

            // PHPCS - Content already escaped
            echo AssetManager::getJs(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }
    }

    $externals = esc_attr(Settings::get('external-css-files'));
    if (!empty($externals)) {
        $externals = explode("\n", $externals);
        foreach ($externals as $external) {
            echo "<link rel='stylesheet' href='" . esc_url($external) . "' type='text/css' media='all' />";
        }
    }
    ?>
</head>
<body>
<?php


// PHPCS - Content already escaped
echo $this->getSliderHTML(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
?>
<script>

    _N2.r('windowLoad', function () {
        if (window.n2ss) {
            var body = document.body,
                options = {
                    forceFullWidth: 0,
                    fullPage: 0,
                    focusOffsetTop: '',
                    focusOffsetBottom: '',
                    margin: 0,
                    height: 0
                },
                setOption = function (name, value) {
                    if (options[name] != value) {
                        options[name] = value;
                        parent.postMessage({
                            key: 'option',
                            name: name,
                            value: value
                        }, "*");
                    }
                },
                sliders = [],
                promise = new Promise(function (resolve) {
                    var checkSliders = function () {
                            if (Object.keys(n2ss.sliders).length) {
                                initSliders();
                            } else {
                                setTimeout(checkSliders, 16);
                            }
                        },
                        initSliders = function () {
                            var promises = [];
                            for (var k in n2ss.sliders) {
                                promises.push(new Promise(function (resolve) {
                                    n2ss.ready(k, (function (slider) {
                                        sliders.push(slider);
                                        resolve();
                                    }).bind(this));
                                }));
                            }

                            Promise.all(promises).then(resolve);
                        };

                    checkSliders();
                });

            promise.then(function () {

                if (sliders.length === 1) {
                    var sliderElement = sliders[0].sliderElement,
                        marginElement = sliderElement.closest('.n2-ss-margin');

                    if (marginElement) {
                        var cs = window.getComputedStyle(marginElement);
                        setOption('margin', [cs.marginTop, cs.marginRight, cs.marginBottom, cs.marginLeft].join(' '));
                        marginElement.style.margin = '0';
                    }
                }

                for (var i = 0; i < sliders.length; i++) {
                    var slider = sliders[i];
                    slider.stages.done('ResizeFirst', (function (slider) {
                        if (slider.sliderElement.closest('ss3-force-full-width')) {
                            setOption('forceFullWidth', true);
                        }

                        if (slider.responsive.parameters.type === 'fullpage') {
                            setOption('fullPage', true);
                        }

                        if (sliders.length === 1) {
                            setOption('focusOffsetTop', slider.responsive.parameters.focus.offsetTop);
                            setOption('focusOffsetBottom', slider.responsive.parameters.focus.offsetBottom);
                        }
                    }).bind(this, slider));

                    slider.stages.done('HasDimension', function () {
                        document.querySelectorAll('a:not([target="_parent"]):not([target="_blank"])').forEach(function (a) {
                            a.target = '_parent';
                        });
                    });
                }

                var observer = new ResizeObserver((function (entries) {
                    setOption('height', entries[0].contentRect.height);
                }).bind(this));

                observer.observe(body);
            });

            var interval = setInterval(function () {
                parent.postMessage({key: 'ready'}, "*");
            }, 40);
            window.addEventListener("message", function (e) {
                var data = e.data;
                switch (data["key"]) {
                    case "ackReady":
                        window.n2Height = data.windowInnerHeight;
                        window.n2OffsetTop = 0;
                        window.n2OffsetBottom = 0;
                        clearInterval(interval);

                        document.body.style.setProperty('--target-height', window.n2Height + 'px');
                        break;
                    case 'fullpage':
                        window.n2Height = data.height;
                        window.n2OffsetTop = data.offsetTop;
                        window.n2OffsetBottom = data.offsetBottom;

                        document.body.style.setProperty('--target-height', window.n2Height + 'px');
                        window.dispatchEvent(new Event('resize'));
                        break;
                }
            });

            n2const.setLocation = function (l) {
                parent.postMessage({
                    key: 'setLocation',
                    location: l
                }, "*");
            };
        }
    });
</script>
</body>
</html>


