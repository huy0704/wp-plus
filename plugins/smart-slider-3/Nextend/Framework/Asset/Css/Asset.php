<?php

namespace Nextend\Framework\Asset\Css;

use Nextend\Framework\Asset\AbstractAsset;
use Nextend\Framework\Asset\Fonts\Google\Google;
use Nextend\Framework\Platform\Platform;
use Nextend\Framework\Plugin;
use Nextend\Framework\Settings;
use Nextend\Framework\Url\Url;
use Nextend\Framework\View\Html;
use Nextend\SmartSlider3\SmartSlider3Info;

class Asset extends AbstractAsset {


    public function __construct() {
        $this->cache = new Cache();
    }

    public function getOutput() {

        $headerPreload = !!Settings::get('header-preload', '0');

        $needProtocol = !Settings::get('protocol-relative', '1');

        Google::build();

        Less\Less::build();

        $output = "";

        $this->urls = array_unique($this->urls);


        foreach ($this->staticGroupPreload as $file) {
            $url    = $this->filterSrc(Url::pathToUri($file, $needProtocol) . '?ver=' . SmartSlider3Info::$revisionShort);
            $output .= Html::style($url, true, array(
                    'media' => 'all'
                )) . "\n";
            if ($headerPreload) {
                header('Link: <' . $url . '>; rel=preload; as=style', false);
            }
        }

        $linkAttributes = array(
            'media' => 'all'
        );

        if (!Platform::isAdmin() && Settings::get('async-non-primary-css', 0)) {
            $linkAttributes = array(
                'media'  => 'print',
                'onload' => "this.media='all'"
            );
        }

        foreach ($this->urls as $url) {

            $url = $this->filterSrc($url);

            $output .= Html::style($url, true, $linkAttributes) . "\n";
        }

        foreach ($this->getFiles() as $file) {
            if (substr($file, 0, 2) == '//') {
                $url = $this->filterSrc($file);
            } else {
                $url = $this->filterSrc(Url::pathToUri($file, $needProtocol) . '?ver=' . SmartSlider3Info::$revisionShort);
            }
            $output .= Html::style($url, true, $linkAttributes) . "\n";
        }

        $inlineText = '';
        foreach ($this->inline as $key => $value) {
            if (!is_numeric($key)) {
                $output .= Html::style($value, false, array(
                        'data-related' => $key
                    )) . "\n";
            } else {
                $inlineText .= $value;
            }
        }

        if (!empty($inlineText)) {
            $output .= Html::style($inlineText) . "\n";
        }

        return $output;
    }

    private function filterSrc($src) {
        return Plugin::applyFilters('n2_style_loader_src', $src);
    }

    public function get() {
        Google::build();
        Less\Less::build();

        return array(
            'url'    => $this->urls,
            'files'  => array_merge($this->staticGroupPreload, $this->getFiles()),
            'inline' => implode("\n", $this->inline)
        );
    }

    public function getAjaxOutput() {

        $output = implode("\n", $this->inline);

        return $output;
    }
}