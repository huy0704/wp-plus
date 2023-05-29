<?php

namespace Nextend\Framework\Form\Element\Radio;

use Nextend\Framework\Filesystem\Filesystem;
use Nextend\Framework\Form\AbstractField;
use Nextend\Framework\Form\ContainerInterface;
use Nextend\Framework\Form\TraitFieldset;

class ImageListFromFolder extends ImageList implements ContainerInterface {

    use TraitFieldset;

    protected $folder = '';

    protected $filenameOnly = false;

    protected function fetchElement() {

        $this->initOptions();

        return parent::fetchElement();
    }

    private function initOptions() {

        $value        = $this->getValue();
        $currentValue = basename($value);
        if ($value !== $currentValue) {
            $this->setValue($currentValue);
        }


        $files = Filesystem::files($this->folder);
        for ($i = 0; $i < count($files); $i++) {
            $ext        = pathinfo($files[$i], PATHINFO_EXTENSION);
            $extensions = array(
                'jpg',
                'jpeg',
                'png',
                'svg',
                'gif',
                'webp'
            );
            if (in_array($ext, $extensions)) {

                $path = $this->folder . $files[$i];

                if ($this->filenameOnly) {
                    $value = pathinfo($files[$i], PATHINFO_FILENAME);
                } else {
                    $value = basename($files[$i]);
                }

                $this->options[$value] = array(
                    'path' => $path
                );
            }
        }

        if (!isset($this->options[$currentValue])) {
            foreach ($this->options as $value => $option) {
                if (pathinfo($value, PATHINFO_FILENAME) == $currentValue) {
                    $currentValue = $value;
                    $this->setValue($currentValue);
                }
            }
        }
    }

    protected function postHTML() {
        if ($this->first) {
            $html = '<div class="n2_field_image_list__fields">';

            $element = $this->first;
            while ($element) {
                $html .= $this->decorateElement($element);

                $element = $element->getNext();
            }

            $html .= '</div>';

            return $html;
        }

        return '';
    }

    public function setFolder($folder) {
        $this->folder = $folder;
    }

    public function setFilenameOnly($value) {
        $this->filenameOnly = $value;
    }

    /**
     * @param AbstractField $element
     *
     * @return string
     */
    public function decorateElement($element) {
        $html = '<div class="n2_field">';
        $html .= '<div class="n2_field__label">';
        $html .= $element->fetchTooltip();
        $html .= '</div>';
        $html .= '<div class="n2_field__element">';
        $html .= $element->fetchElement();
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }
}