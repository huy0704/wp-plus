<?php


namespace Nextend\Framework\Form\Element\Select;


use Nextend\Framework\Form\Element\Select;
use Nextend\Framework\Platform\Platform;

class SelectFile extends Select {

    /**
     * File constructor.
     *
     * @param        $insertAt
     * @param string $name
     * @param string $label
     * @param string $default
     * @param string $extension
     * @param array  $parameters
     *
     */
    public function __construct($insertAt, $name = '', $label = '', $default = '', $extension = '', $parameters = array()) {
        parent::__construct($insertAt, $name, $label, $default, $parameters);

        $dir             = Platform::getPublicDirectory();
        $files           = scandir($dir);
        $validated_files = array();

        foreach ($files as $file) {
            if (strtolower(pathinfo($file, PATHINFO_EXTENSION)) == $extension) {
                $validated_files[] = $file;
            }
        }

        $this->options[''] = n2_('Choose');

        foreach ($validated_files as $f) {
            $this->options[$f] = $f;
        }
    }
}