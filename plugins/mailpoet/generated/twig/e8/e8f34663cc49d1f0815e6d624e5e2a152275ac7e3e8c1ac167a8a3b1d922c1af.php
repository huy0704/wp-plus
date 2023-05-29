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

/* welcome_wizard.html */
class __TwigTemplate_040602ebc7669cdfb9da26116daadadf72855abd5f9690ec0e527b909dcfd9c2 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'content' => [$this, 'block_content'],
            'translations' => [$this, 'block_translations'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 1
        return "layout.html";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        $this->parent = $this->loadTemplate("layout.html", "welcome_wizard.html", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 4
        echo "<script>
  var mailpoet_logo_url = '";
        // line 5
        echo $this->extensions['MailPoet\Twig\Assets']->generateCdnUrl("welcome-wizard/mailpoet-logo.20200623.png");
        echo "';
  var wizard_sender_illustration_url = '";
        // line 6
        echo $this->extensions['MailPoet\Twig\Assets']->generateCdnUrl("welcome-wizard/sender.20200623.png");
        echo "';
  var wizard_tracking_illustration_url = '";
        // line 7
        echo $this->extensions['MailPoet\Twig\Assets']->generateCdnUrl("welcome-wizard/tracking.20200623.png");
        echo "';
  var wizard_woocommerce_illustration_url = '";
        // line 8
        echo $this->extensions['MailPoet\Twig\Assets']->generateCdnUrl("welcome-wizard/woocommerce.20200623.png");
        echo "';
  var wizard_MSS_pitch_illustration_url = '";
        // line 9
        echo $this->extensions['MailPoet\Twig\Assets']->generateCdnUrl("welcome-wizard/illu-pitch-mss.20190912.png");
        echo "';
  var finish_wizard_url = '";
        // line 10
        echo \MailPoetVendor\twig_escape_filter($this->env, ($context["finish_wizard_url"] ?? null), "html", null, true);
        echo "';
  var admin_email = ";
        // line 11
        echo json_encode(($context["admin_email"] ?? null));
        echo ";
  var hide_mailpoet_beacon = true;
  var mailpoet_show_customers_import = ";
        // line 13
        echo json_encode(($context["show_customers_import"] ?? null));
        echo ";
  var mailpoet_account_url = '";
        // line 14
        echo $this->extensions['MailPoet\Twig\Functions']->addReferralId(((("https://account.mailpoet.com/?s=" . ($context["subscriber_count"] ?? null)) . "&email=") . \MailPoetVendor\twig_escape_filter($this->env, \MailPoetVendor\twig_get_attribute($this->env, $this->source, ($context["current_wp_user"] ?? null), "user_email", [], "any", false, false, false, 14), "js")));
        echo "';
  var mailpoet_settings = ";
        // line 15
        echo json_encode(($context["settings"] ?? null));
        echo ";
  var mailpoet_premium_key_valid = ";
        // line 16
        echo json_encode(($context["premium_key_valid"] ?? null));
        echo ";
  var mailpoet_mss_key_valid = ";
        // line 17
        echo json_encode(($context["mss_key_valid"] ?? null));
        echo ";
</script>

<div id=\"mailpoet-wizard-container\"></div>

<div class=\"mailpoet-wizard-video\">
    <iframe width=\"1\" height=\"1\" src=\"https://player.vimeo.com/video/279123953\" frameborder=\"0\"></iframe>
</div>

";
    }

    // line 28
    public function block_translations($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 29
        echo $this->extensions['MailPoet\Twig\I18n']->localize(["welcomeWizardLetsStartTitle" => $this->extensions['MailPoet\Twig\I18n']->translate("Start by configuring your sender information"), "welcomeWizardSenderTitle" => $this->extensions['MailPoet\Twig\I18n']->translate("Default sender"), "welcomeWizardSenderText" => $this->extensions['MailPoet\Twig\I18n']->translate("Enter details of the person or brand your subscribers expect to receive emails from"), "welcomeWizardUsageTrackingStepTitle" => $this->extensions['MailPoet\Twig\I18n']->translate("Confirm privacy and data settings"), "welcomeWizardUsageTrackingStepTrackingLabel" => $this->extensions['MailPoet\Twig\I18n']->translate("Help improve MailPoet"), "welcomeWizardUsageTrackingStepTrackingLabelNote" => $this->extensions['MailPoet\Twig\I18n']->translate("Get improved features and fixes faster by sharing with us [link]non-sensitive data about how you use MailPoet[/link]. No personal data is tracked or stored."), "welcomeWizardUsageTrackingStepTrackingLabelNoteNote" => $this->extensions['MailPoet\Twig\I18n']->translate("Note"), "welcomeWizardUsageTrackingStepLibs3rdPartyLabelNote" => $this->extensions['MailPoet\Twig\I18n']->translate("If enabled, we may load the Google Fonts library and [link]other 3rd-party libraries we use[/link]."), "welcomeWizardUsageTrackingStepLibs3rdPartyLabelNoteNote" => $this->extensions['MailPoet\Twig\I18n']->translate("Note"), "welcomeWizardUsageTrackingStepLibs3rdPartyLabel" => $this->extensions['MailPoet\Twig\I18n']->translate("Enable better-looking Google Fonts in forms and emails and show contextual help articles in MailPoet?"), "seeVideoGuide" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("See video guide", "A label on a button"), "skip" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Skip", "A label on a skip button"), "skipStep" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Skip this step", "A label on a skip button"), "senderAddress" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("From Address", "A form field label"), "replyToAddress" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Reply-to Address", "A form field label"), "senderName" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("From Name", "A form field label"), "next" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Next", "A label on a button"), "continue" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Continue", "A label on a button")]);
        // line 48
        echo "
";
        // line 49
        $this->loadTemplate("mss_pitch_translations.html", "welcome_wizard.html", 49)->display($context);
        // line 50
        $this->loadTemplate("woocommerce_setup_translations.html", "welcome_wizard.html", 50)->display($context);
        // line 51
        $this->loadTemplate("premium_key_validation_strings.html", "welcome_wizard.html", 51)->display($context);
    }

    public function getTemplateName()
    {
        return "welcome_wizard.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  126 => 51,  124 => 50,  122 => 49,  119 => 48,  117 => 29,  113 => 28,  99 => 17,  95 => 16,  91 => 15,  87 => 14,  83 => 13,  78 => 11,  74 => 10,  70 => 9,  66 => 8,  62 => 7,  58 => 6,  54 => 5,  51 => 4,  47 => 3,  36 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "welcome_wizard.html", "/home/circleci/mailpoet/mailpoet/views/welcome_wizard.html");
    }
}
