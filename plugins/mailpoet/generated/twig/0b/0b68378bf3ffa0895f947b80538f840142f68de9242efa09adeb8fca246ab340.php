<?php

if (!defined('ABSPATH')) exit;


use MailPoetVendor\Twig\Environment;
use MailPoetVendor\Twig\Error\LoaderError;
use MailPoetVendor\Twig\Error\RuntimeError;
use MailPoetVendor\Twig\Extension\SandboxExtension;
use MailPoetVendor\Twig\Markup;
use MailPoetVendor\Twig\Sandbox\SecurityError;
use MailPoetVendor\Twig\Sandbox\SecurityNotAllowedTagError;
use MailPoetVendor\Twig\Sandbox\SecurityNotAllowedFilterError;
use MailPoetVendor\Twig\Sandbox\SecurityNotAllowedFunctionError;
use MailPoetVendor\Twig\Source;
use MailPoetVendor\Twig\Template;

/* woocommerce_setup_translations.html */
class __TwigTemplate_c95c0ee3fde12b086b24086defc4b25cb76fceb1caeaf4a25eb295bc17e32b2a extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo $this->extensions['MailPoet\Twig\I18n']->localize(["wooCommerceSetupTitle" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Power up your WooCommerce store", "Title on the WooCommerce setup page"), "wooCommerceSetupInfo" => $this->extensions['MailPoet\Twig\I18n']->translate("MailPoet comes with powerful features for WooCommerce. Select features that you would like to use with your store."), "wooCommerceSetupGDPRTag" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("GDPR", "WooCommerce setup GDPR tag"), "wooCommerceSetupImportInfo" => $this->extensions['MailPoet\Twig\I18n']->translate("Do you want to import your WooCommerce customers as subscribed? [link]Learn more[/link]."), "wooCommerceSetupImportGDPRInfo" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("To be compliant with privacy regulations, your customers must have explicitly accepted to receive your marketing emails.", "GDPR compliance information"), "wooCommerceSetupTrackingInfo" => $this->extensions['MailPoet\Twig\I18n']->translate("Collect more precise email and site engagement, and e-commerce metrics by enabling cookie tracking. [link]Learn more[/link]."), "wooCommerceSetupTrackingGDPRInfo" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("To be compliant, you should display a cookie tracking banner on your website.", "GDPR compliance information"), "wooCommerceSetupFinishButtonTextWizard" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Start using MailPoet", "Submit button caption in the WooCommerce step in the wizard"), "wooCommerceSetupFinishButtonTextStandalone" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Start using WooCommerce features", "Submit button caption on the standalone WooCommerce setup page"), "unknownError" => $this->extensions['MailPoet\Twig\I18n']->translate("Unknown error")]);
        // line 12
        echo "
";
    }

    public function getTemplateName()
    {
        return "woocommerce_setup_translations.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  39 => 12,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "woocommerce_setup_translations.html", "/home/circleci/mailpoet/mailpoet/views/woocommerce_setup_translations.html");
    }
}
