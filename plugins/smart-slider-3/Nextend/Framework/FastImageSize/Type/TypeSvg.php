<?php

namespace Nextend\Framework\FastImageSize\Type;

class TypeSvg extends TypeBase {

    /**
     * {@inheritdoc}
     */
    public function getSize($filename) {

        $data = $this->fastImageSize->getImage($filename, 0, 100);

        preg_match('/width="([0-9]+)"/', $data, $matches);
        if ($matches && $matches[1] > 0) {
            $size          = array();
            $size['width'] = $matches[1];

            preg_match('/height="([0-9]+)"/', $data, $matches);
            if ($matches && $matches[1] > 0) {
                $size['height'] = $matches[1];
                $this->fastImageSize->setSize($size);

                return;
            }
        }

        preg_match('/viewBox=["\']([0-9]+) ([0-9]+) ([0-9]+) ([0-9]+)["\']/i', $data, $matches);

        if ($matches) {
            $this->fastImageSize->setSize(array(
                'width'  => $matches[3] - $matches[1],
                'height' => $matches[4] - $matches[2],
            ));
        }

    }
}
