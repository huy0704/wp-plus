<?php


namespace Nextend\Framework\Form\Insert;

use Nextend\Framework\Form\ContainedInterface;
use Nextend\Framework\Form\ContainerInterface;

abstract class AbstractInsert {

    /**
     * @var ContainedInterface
     */
    protected $at;

    /**
     * AbstractInsert constructor.
     *
     * @param ContainedInterface $at
     */
    public function __construct($at) {
        $this->at = $at;
    }

    /**
     * @param ContainedInterface $element
     *
     * @return ContainerInterface Returns the parent
     */
    public abstract function insert($element);
}