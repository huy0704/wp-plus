<?php


namespace Nextend\Framework\Asset\Fonts\Google;


use Nextend\Framework\Asset\AbstractAsset;
use Nextend\Framework\Asset\Css\Css;
use Nextend\Framework\Cache\CacheGoogleFont;
use Nextend\Framework\Filesystem\Filesystem;
use Nextend\Framework\Font\FontSettings;
use Nextend\Framework\Url\UrlHelper;
use Nextend\SmartSlider3\SmartSlider3Info;

class Asset extends AbstractAsset {

    public function getLoadedFamilies() {
        return array_keys($this->files);
    }

    function addFont($family, $style = '400') {
        $style = (string)$style;
        if (!isset($this->files[$family])) {
            $this->files[$family] = array();
        }
        if (!in_array($style, $this->files[$family])) {
            $this->files[$family][] = $style;
        }
    }

    public function loadFonts() {

        if (!empty($this->files)) {
            //https://fonts.googleapis.com/css?display=swap&family=Montserrat:400%7CRoboto:100italic,300,400

            $families = array();
            foreach ($this->files as $name => $styles) {
                if (count($styles) && !in_array($name, Google::$excludedFamilies)) {
                    $families[] = $name . ':' . implode(',', $styles);
                }
            }

            if (count($families)) {
                $params = array(
                    'display' => 'swap',
                    'family'  => urlencode(implode('|', $families))
                );

                $url = UrlHelper::add_query_arg($params, 'https://fonts.googleapis.com/css');

                $fontSettings = FontSettings::getPluginsData();
                if ($fontSettings->get('google-cache', 0)) {
                    $cache = new CacheGoogleFont();

                    $path = $cache->makeCache($url, 'css');

                    if ($path) {
                        $url = Filesystem::pathToAbsoluteURL($path) . '?ver=' . SmartSlider3Info::$revisionShort;
                    }
                }

                Css::addUrl($url);
            }

        }

        return true;
    }
}