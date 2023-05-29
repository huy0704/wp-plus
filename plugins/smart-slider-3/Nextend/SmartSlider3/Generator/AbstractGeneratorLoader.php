<?php

namespace Nextend\SmartSlider3\Generator;

use Nextend\Framework\Filesystem\Filesystem;
use ReflectionClass;
use ReflectionException;

abstract class AbstractGeneratorLoader {

    public function __construct() {

        try {
            $reflectionClass = new ReflectionClass($this);
            $namespace       = $reflectionClass->getNamespaceName();

            $dir = dirname($reflectionClass->getFileName());

            foreach (Filesystem::folders($dir) as $name) {
                $className = '\\' . $namespace . '\\' . $name . '\\GeneratorGroup' . $name;

                if (class_exists($className)) {
                    new $className;
                }
            }
        } catch (ReflectionException $e) {

        }
    }
}