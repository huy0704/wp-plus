<?php

namespace Nextend\Framework\Pattern;

trait OrderableTrait {

    public function getOrdering() {

        return isset($this->ordering) ? $this->ordering : 1000000;
    }

    /**
     * @param OrderableTrait[] $items
     */
    public static function uasort(&$items) {
        uasort($items, array(
            OrderableTrait::class,
            'compare'
        ));
    }

    /**
     * @param OrderableTrait $a
     * @param OrderableTrait $b
     *
     * @return int
     */
    public static function compare($a, $b) {
        return $a->getOrdering() - $b->getOrdering();
    }
}