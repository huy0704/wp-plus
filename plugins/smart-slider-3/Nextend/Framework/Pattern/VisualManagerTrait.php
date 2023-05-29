<?php


namespace Nextend\Framework\Pattern;


use Nextend\Framework\Plugin;

trait VisualManagerTrait {

    /** @var MVCHelperTrait */
    protected $MVCHelper;

    /**
     * StyleManager constructor.
     *
     * @param MVCHelperTrait $MVCHelper
     */
    public function __construct($MVCHelper) {
        $this->MVCHelper = $MVCHelper;

        Plugin::addAction('afterApplicationContent', array(
            $this,
            'display'
        ));
    }

    public abstract function display();

    /**
     * @param MVCHelperTrait $MVCHelper
     */
    public static function enqueue($MVCHelper) {
        static $enqueued;

        if (!$enqueued) {
            new self($MVCHelper);
            $enqueued = true;
        }
    }
}