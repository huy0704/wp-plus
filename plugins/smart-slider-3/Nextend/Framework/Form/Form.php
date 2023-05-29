<?php


namespace Nextend\Framework\Form;


use Nextend\Framework\Data\Data;
use Nextend\Framework\Form\Fieldset\FieldsetHidden;
use Nextend\Framework\Pattern\MVCHelperTrait;

class Form extends Data {

    use MVCHelperTrait;

    protected static $counter = 1;

    /** @var Base\PlatformFormBase */
    private static $platformForm;

    protected $id;

    /**
     * @var Data
     */
    protected $context;

    protected $controlName = '';

    /**
     * @var ContainerMain
     */
    protected $container;

    protected $classes = array(
        'n2_form'
    );

    /**
     * Form constructor.
     *
     * @param MVCHelperTrait $MVCHelper
     * @param string         $controlName
     */
    public function __construct($MVCHelper, $controlName) {

        $this->id = 'n2_form_' . self::$counter++;

        $this->controlName = $controlName;

        $this->setMVCHelper($MVCHelper);

        $this->context = new Data();
        parent::__construct();

        $this->container = new ContainerMain($this);
    }

    /**
     * @return ContainerMain
     */
    public function getContainer() {
        return $this->container;
    }

    public function getId() {
        return $this->id;
    }

    /**
     * @return Data
     */
    public function getContext() {
        return $this->context;
    }

    /**
     * @param $path
     *
     * @return ContainerInterface|AbstractField
     */
    public function getElement($path) {

        /**
         * Remove starting / path separator
         */
        return $this->container->getElement(substr($path, 1));
    }

    public function render() {
        echo '<div class="' . esc_attr(implode(' ', $this->classes)) . '">';

        $this->container->renderContainer();

        echo '</div>';
    }

    /**
     * @return string
     */
    public function getControlName() {
        return $this->controlName;
    }

    public static function init() {
        self::$platformForm = new WordPress\PlatformForm();
    }

    public static function tokenize() {
        return self::$platformForm->tokenize();
    }

    public static function tokenizeUrl() {
        return self::$platformForm->tokenizeUrl();
    }

    public static function checkToken() {
        return self::$platformForm->checkToken();
    }

    /**
     * @return FieldsetHidden
     */
    public function getFieldsetHidden() {
        return $this->container->getFieldsetHidden();
    }

    public function setDark() {
        $this->classes[] = 'n2_form--dark';
    }

    public function addClass($className) {
        $this->classes[] = $className;
    }
}

Form::init();