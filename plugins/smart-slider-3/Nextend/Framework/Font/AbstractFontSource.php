<?php


namespace Nextend\Framework\Font;


use Nextend\Framework\Form\ContainerInterface;

abstract class AbstractFontSource {

    protected $name;

    public abstract function getLabel();

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    public function onFontManagerLoad($force = false) {

    }

    public function onFontManagerLoadBackend() {
    }

    /**
     * @param ContainerInterface $container
     */
    abstract public function renderFields($container);
}