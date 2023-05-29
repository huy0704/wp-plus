<?php

namespace Nextend\Framework\Asset\Js;

use Nextend\Framework\Asset\AbstractCache;
use Nextend\Framework\Cache\Manifest;

class Cache extends AbstractCache {

    public $outputFileType = "js";

    /**
     * @param Manifest $cache
     *
     * @return string
     */
    public function getCachedContent($cache) {

        $content = '(function(){this._N2=this._N2||{_r:[],_d:[],r:function(){this._r.push(arguments)},d:function(){this._d.push(arguments)}}}).call(window);';
        $content .= parent::getCachedContent($cache);
        $content .= "_N2.d('" . $this->group . "');";

        return $content;
    }
}