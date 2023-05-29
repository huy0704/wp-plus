<?php

namespace Nextend\Framework\Asset;

use Nextend\Framework\Misc\Base64;

class AbstractAsset {

    /**
     * @var AbstractCache
     */
    protected $cache;

    protected $files = array();
    protected $urls = array();
    protected $codes = array();
    protected $globalInline = array();
    protected $firstCodes = array();
    protected $inline = array();
    protected $staticGroupPreload = array();
    protected $staticGroup = array();

    protected $groups = array();

    public function addFile($pathToFile, $group) {
        $this->addGroup($group);
        $this->files[$group][] = $pathToFile;
    }

    public function addFiles($path, $files, $group) {
        $this->addGroup($group);
        foreach ($files as $file) {
            $this->files[$group][] = $path . DIRECTORY_SEPARATOR . $file;
        }
    }

    public function addStaticGroupPreload($file, $group) {
        $this->staticGroupPreload[$group] = $file;
    }

    public function addStaticGroup($file, $group) {
        $this->staticGroup[$group] = $file;
    }

    private function addGroup($group) {
        if (!isset($this->files[$group])) {
            $this->files[$group] = array();
        }
    }

    public function addCode($code, $group, $unshift = false) {
        if (!isset($this->codes[$group])) {
            $this->codes[$group] = array();
        }
        if (!$unshift) {
            $this->codes[$group][] = $code;
        } else {
            array_unshift($this->codes[$group], $code);
        }
    }

    public function addUrl($url) {
        $this->urls[] = $url;
    }

    public function addFirstCode($code, $unshift = false) {
        if ($unshift) {
            array_unshift($this->firstCodes, $code);
        } else {
            $this->firstCodes[] = $code;
        }
    }

    public function addInline($code, $name = null, $unshift = false) {
        if ($unshift) {
            array_unshift($this->inline, $code);

        } else {
            if ($name) {
                $this->inline[$name] = $code;
            } else {
                $this->inline[] = $code;
            }
        }
    }

    public function addGlobalInline($code, $unshift = false) {
        if ($unshift) {
            array_unshift($this->globalInline, $code);
        } else {
            $this->globalInline[] = $code;
        }
    }

    public function loadedFilesEncoded() {
        return Base64::encode(json_encode(call_user_func_array('array_merge', $this->files) + $this->urls));
    }

    protected function uniqueFiles() {
        foreach ($this->files as $group => &$files) {
            $this->files[$group] = array_values(array_unique($files));
        }
        $this->initGroups();
    }

    public function removeFiles($notNeededFiles) {
        foreach ($this->files as $group => &$files) {
            $this->files[$group] = array_diff($files, $notNeededFiles);
        }
    }

    public function initGroups() {
        $this->groups = array_unique(array_merge(array_keys($this->files), array_keys($this->codes)));

        $skeleton = array_map(array(
            AbstractAsset::class,
            'emptyArray'
        ), array_flip($this->groups));

        $this->files += $skeleton;
        $this->codes += $skeleton;
    }

    private static function emptyArray() {
        return array();
    }

    public function getFiles() {
        $this->uniqueFiles();

        $files = array();

        if (AssetManager::$cacheAll) {
            foreach ($this->groups as $group) {
                if (isset($this->staticGroup[$group])) continue;
                $files[$group] = $this->cache->getAssetFile($group, $this->files[$group], $this->codes[$group]);
            }
        } else {
            foreach ($this->groups as $group) {
                if (isset($this->staticGroup[$group])) continue;
                if (in_array($group, AssetManager::$cachedGroups)) {
                    $files[$group] = $this->cache->getAssetFile($group, $this->files[$group], $this->codes[$group]);
                } else {
                    foreach ($this->files[$group] as $file) {
                        $files[] = $file;
                    }
                    foreach ($this->codes[$group] as $code) {
                        array_unshift($this->inline, $code);
                    }
                }
            }
        }

        if (isset($files['n2'])) {
            return array('n2' => $files['n2']) + $this->staticGroup + $files;
        }

        return array_merge($files, $this->staticGroup);
    }

    public function serialize() {
        return array(
            'staticGroupPreload' => $this->staticGroupPreload,
            'staticGroup'        => $this->staticGroup,
            'files'              => $this->files,
            'urls'               => $this->urls,
            'codes'              => $this->codes,
            'firstCodes'         => $this->firstCodes,
            'inline'             => $this->inline,
            'globalInline'       => $this->globalInline
        );
    }

    public function unSerialize($array) {
        $this->staticGroupPreload = array_merge($this->staticGroupPreload, $array['staticGroupPreload']);
        $this->staticGroup        = array_merge($this->staticGroup, $array['staticGroup']);

        foreach ($array['files'] as $group => $files) {
            if (!isset($this->files[$group])) {
                $this->files[$group] = $files;
            } else {
                $this->files[$group] = array_merge($this->files[$group], $files);
            }
        }
        $this->urls = array_merge($this->urls, $array['urls']);

        foreach ($array['codes'] as $group => $codes) {
            if (!isset($this->codes[$group])) {
                $this->codes[$group] = $codes;
            } else {
                $this->codes[$group] = array_merge($this->codes[$group], $codes);
            }
        }

        $this->firstCodes   = array_merge($this->firstCodes, $array['firstCodes']);
        $this->inline       = array_merge($this->inline, $array['inline']);
        $this->globalInline = array_merge($this->globalInline, $array['globalInline']);
    }
}