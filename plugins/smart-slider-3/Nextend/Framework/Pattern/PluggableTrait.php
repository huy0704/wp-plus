<?php


namespace Nextend\Framework\Pattern;


use Nextend\Framework\Plugin;

trait PluggableTrait {

    protected function makePluggable($id) {

        Plugin::doAction('PluggableFactory' . $id, array($this));
    }
}