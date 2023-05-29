<?php

namespace Nextend\Framework\Cache;

class StoreImage extends AbstractCache {

    protected $_storageEngine = 'filesystem';

    protected function getScope() {
        return 'image';
    }

    public function makeCache($fileName, $content) {
        if (!$this->isImage($fileName)) {
            return false;
        }

        if (!$this->exists($fileName)) {
            $this->set($fileName, $content);
        }

        return $this->getPath($fileName);
    }

    private function isImage($fileName) {
        $supported_image = array(
            'gif',
            'jpg',
            'jpeg',
            'png',
            'mp4',
            'mp3',
            'webp',
            'svg'
        );

        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        if (in_array($ext, $supported_image)) {
            return true;
        }

        return false;
    }
}