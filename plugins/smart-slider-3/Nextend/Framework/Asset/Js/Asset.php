<?php


namespace Nextend\Framework\Asset\Js;

use Nextend\Framework\Asset\AbstractAsset;
use Nextend\Framework\Localization\Localization;
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

        $output = "";

        $needProtocol = !Settings::get('protocol-relative', '1');

        $globalInline = $this->getGlobalInlineScripts();
        if (!empty($globalInline)) {
            $output .= Html::script(self::minify_js($globalInline . "\n"));
        }

        $async            = !Platform::isAdmin();
        $scriptAttributes = array();
        if ($async) {
            $scriptAttributes['defer'] = 1;
            $scriptAttributes['async'] = 1;
        }

        foreach ($this->urls as $url) {
            $output .= Html::scriptFile($this->filterSrc($url), $scriptAttributes) . "\n";
        }

        foreach ($this->getFiles() as $file) {
            if (substr($file, 0, 2) == '//') {
                $output .= Html::scriptFile($this->filterSrc($file), $scriptAttributes) . "\n";
            } else {
                $output .= Html::scriptFile($this->filterSrc(Url::pathToUri($file, $needProtocol) . '?ver=' . SmartSlider3Info::$revisionShort), $scriptAttributes) . "\n";
            }
        }

        $output .= Html::script(self::minify_js(Localization::toJS() . "\n" . $this->getInlineScripts() . "\n"));

        return $output;
    }

    private function filterSrc($src) {
        return Plugin::applyFilters('n2_script_loader_src', $src);
    }

    public function get() {
        return array(
            'url'          => $this->urls,
            'files'        => $this->getFiles(),
            'inline'       => $this->getInlineScripts(),
            'globalInline' => $this->getGlobalInlineScripts()
        );
    }

    public function getAjaxOutput() {

        $output = $this->getInlineScripts();

        return $output;
    }

    private function getGlobalInlineScripts() {
        return implode('', $this->globalInline);
    }

    private function getInlineScripts() {
        $scripts = '';

        foreach ($this->firstCodes as $script) {
            $scripts .= $script . "\n";
        }

        foreach ($this->inline as $script) {
            $scripts .= $script . "\n";
        }

        return $this->serveJquery($scripts);
    }

    public static function serveJquery($script) {
        if (empty($script)) {
            return "";
        }
        $inline = "_N2.r('documentReady', function(){\n";
        $inline .= $script;
        $inline .= "});\n";

        return $inline;
    }

    public static function minify_js($input) {
        if (trim($input) === "") return $input;

        return preg_replace(array(
            // Remove comment(s)
            '#\s*("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')\s*|\s*\/\*(?!\!|@cc_on)(?>[\s\S]*?\*\/)\s*|\s*(?<![\:\=])\/\/.*(?=[\n\r]|$)|^\s*|\s*$#',
            // Remove white-space(s) outside the string and regex
            '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/)|\/(?!\/)[^\n\r]*?\/(?=[\s.,;]|[gimuy]|$))|\s*([!%&*\(\)\-=+\[\]\{\}|;:,.<>?\/])\s*#s',
            // Remove the last semicolon
            '#;+\}#',
            // Minify object attribute(s) except JSON attribute(s). From `{'foo':'bar'}` to `{foo:'bar'}`
            '#([\{,])([\'])(\d+|[a-z_][a-z0-9_]*)\2(?=\:)#i',
            // --ibid. From `foo['bar']` to `foo.bar`
            '#([a-z0-9_\)\]])\[([\'"])([a-z_][a-z0-9_]*)\2\]#i'
        ), array(
            '$1',
            '$1$2',
            '}',
            '$1$3',
            '$1.$3'
        ), $input);
    }
}