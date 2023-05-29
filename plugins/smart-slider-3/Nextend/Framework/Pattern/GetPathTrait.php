<?php

namespace Nextend\Framework\Pattern;

use ReflectionClass;

trait GetPathTrait {

    public static function getPath() {

        $reflection = new ReflectionClass(static::class);

        return dirname($reflection->getFileName());
    }
}