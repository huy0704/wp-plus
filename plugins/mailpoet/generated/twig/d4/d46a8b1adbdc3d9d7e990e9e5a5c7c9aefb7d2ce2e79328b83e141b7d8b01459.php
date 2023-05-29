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

/* newsletters.html */
class __TwigTemplate_9641d7769a94575cd44aaeff4df310da82a8fe07490b66905fc1e575dbaf51b2 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'content' => [$this, 'block_content'],
            'after_translations' => [$this, 'block_after_translations'],
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
        $this->parent = $this->loadTemplate("layout.html", "newsletters.html", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 4
        echo "  <div id=\"newsletters_container\"></div>

  <script type=\"text/javascript\">
    ";
        // line 8
        echo "      var mailpoet_update_available = ";
        echo ((($context["is_mailpoet_update_available"] ?? null)) ? ("true") : ("false"));
        echo "
      var mailpoet_listing_per_page = ";
        // line 9
        echo \MailPoetVendor\twig_escape_filter($this->env, ($context["items_per_page"] ?? null), "js", null, true);
        echo ";
      var mailpoet_display_nps_poll = ";
        // line 10
        echo ((((($context["sent_newsletters_count"] ?? null) > 0) && \MailPoetVendor\twig_get_attribute($this->env, $this->source, ($context["settings"] ?? null), "display_nps_poll", [], "any", false, false, false, 10))) ? ("true") : ("false"));
        echo ";
      var mailpoet_segments = ";
        // line 11
        echo json_encode(($context["segments"] ?? null));
        echo ";
      var mailpoet_show_congratulate_after_first_newsletter = ";
        // line 12
        echo \MailPoetVendor\twig_escape_filter($this->env, ($context["show_congratulate_after_first_newsletter"] ?? null), "js", null, true);
        echo ";
      var mailpoet_current_wp_user = ";
        // line 13
        echo json_encode(($context["current_wp_user"] ?? null));
        echo ";
      var mailpoet_current_wp_user_firstname = '";
        // line 14
        echo \MailPoetVendor\twig_escape_filter($this->env, ($context["current_wp_user_firstname"] ?? null), "js", null, true);
        echo "';
      var mailpoet_lists = ";
        // line 15
        echo json_encode(($context["lists"] ?? null));
        echo ";
      var mailpoet_roles = ";
        // line 16
        echo json_encode(($context["roles"] ?? null));
        echo ";
      var mailpoet_current_date = ";
        // line 17
        echo json_encode(($context["current_date"] ?? null));
        echo ";
      var mailpoet_tomorrow_date = ";
        // line 18
        echo json_encode(($context["tomorrow_date"] ?? null));
        echo ";
      var mailpoet_current_time = ";
        // line 19
        echo json_encode(($context["current_time"] ?? null));
        echo ";
      var mailpoet_current_date_time = ";
        // line 20
        echo json_encode(($context["current_date_time"] ?? null));
        echo ";
      var mailpoet_schedule_time_of_day = ";
        // line 21
        echo json_encode(($context["schedule_time_of_day"] ?? null));
        echo ";
      var mailpoet_date_display_format = \"";
        // line 22
        echo $this->extensions['MailPoet\Twig\Functions']->getWPDateFormat();
        echo "\";
      var mailpoet_date_storage_format = \"Y-m-d\";
      var mailpoet_product_categories = ";
        // line 24
        echo json_encode(($context["product_categories"] ?? null));
        echo ";
      var mailpoet_products = ";
        // line 25
        echo json_encode(($context["products"] ?? null));
        echo ";

      var mailpoet_account_url = '";
        // line 27
        echo $this->extensions['MailPoet\Twig\Functions']->addReferralId(((("https://account.mailpoet.com/?s=" . ($context["subscriber_count"] ?? null)) . "&email=") . \MailPoetVendor\twig_escape_filter($this->env, \MailPoetVendor\twig_get_attribute($this->env, $this->source, ($context["current_wp_user"] ?? null), "user_email", [], "any", false, false, false, 27), "js")));
        echo "';

      var mailpoet_woocommerce_automatic_emails = ";
        // line 29
        echo json_encode(($context["automatic_emails"] ?? null));
        echo ";
      var mailpoet_woocommerce_optin_on_checkout = \"";
        // line 30
        echo \MailPoetVendor\twig_escape_filter($this->env, ($context["woocommerce_optin_on_checkout"] ?? null), "js", null, true);
        echo "\";

      var mailpoet_woocommerce_transactional_email_id = ";
        // line 32
        echo json_encode(($context["woocommerce_transactional_email_id"] ?? null));
        echo ";
      var mailpoet_display_detailed_stats = ";
        // line 33
        echo json_encode(($context["display_detailed_stats"] ?? null));
        echo ";
      var mailpoet_last_announcement_seen = ";
        // line 34
        echo json_encode(($context["last_announcement_seen"] ?? null));
        echo ";
      var mailpoet_user_locale = '";
        // line 35
        echo $this->extensions['MailPoet\Twig\I18n']->getLocale();
        echo "';
      var mailpoet_congratulations_success_images = [
        '";
        // line 37
        echo $this->extensions['MailPoet\Twig\Assets']->generateCdnUrl("newsletter/congratulate-1.png");
        echo "',
        '";
        // line 38
        echo $this->extensions['MailPoet\Twig\Assets']->generateCdnUrl("newsletter/congratulate-2.png");
        echo "',
        '";
        // line 39
        echo $this->extensions['MailPoet\Twig\Assets']->generateCdnUrl("newsletter/congratulate-3.png");
        echo "',
        '";
        // line 40
        echo $this->extensions['MailPoet\Twig\Assets']->generateCdnUrl("newsletter/congratulate-4.png");
        echo "',
      ];
      var mailpoet_congratulations_error_image = '";
        // line 42
        echo $this->extensions['MailPoet\Twig\Assets']->generateCdnUrl("newsletter/error.png");
        echo "';
      var mailpoet_congratulations_loading_image = '";
        // line 43
        echo $this->extensions['MailPoet\Twig\Assets']->generateCdnUrl("newsletter/congratulation-page-illustration-transparent-LQ.20181121-1440.png");
        echo "';
      var mailpoet_emails_page = '";
        // line 44
        echo \MailPoetVendor\twig_escape_filter($this->env, ($context["mailpoet_email_page"] ?? null), "js", null, true);
        echo "';
      var mailpoet_review_request_illustration_url = '";
        // line 45
        echo $this->extensions['MailPoet\Twig\Assets']->generateCdnUrl("review-request/review-request-illustration.20190815-1427.svg");
        echo "';
      var mailpoet_installed_at = '";
        // line 46
        echo \MailPoetVendor\twig_escape_filter($this->env, \MailPoetVendor\twig_get_attribute($this->env, $this->source, ($context["settings"] ?? null), "installed_at", [], "any", false, false, false, 46), "js", null, true);
        echo "';
      var mailpoet_editor_javascript_url = '";
        // line 47
        echo $this->extensions['MailPoet\Twig\Assets']->getJavascriptScriptUrl("newsletter_editor.js");
        echo "';
      var mailpoet_newsletters_count = ";
        // line 48
        echo \MailPoetVendor\twig_escape_filter($this->env, ($context["newsletters_count"] ?? null), "js", null, true);
        echo ";
      var mailpoet_authorized_emails = ";
        // line 49
        echo json_encode(($context["authorized_emails"] ?? null));
        echo ";
      var mailpoet_verified_sender_domains = ";
        // line 50
        echo json_encode(($context["verified_sender_domains"] ?? null));
        echo ";
      var mailpoet_all_sender_domains = ";
        // line 51
        echo json_encode(($context["all_sender_domains"] ?? null));
        echo ";
    ";
        // line 53
        echo "    var mailpoet_beacon_articles = [
      '57fdc312c697911f2d324fd7',
      '5d541f7c2c7d3a68825ea881',
      '58a719a12c7d3a576d3548da',
      '5a0257ac2c7d3a272c0d7ad6',
      '58f671152c7d3a057f8858e8'
    ];

    var mailpoet_newsletters_templates_recently_sent_count = ";
        // line 61
        echo json_decode(($context["newsletters_templates_recently_sent_count"] ?? null));
        echo ";
    var corrupt_newsletters = ";
        // line 62
        echo json_encode(($context["corrupt_newsletters"] ?? null));
        echo ";

  </script>
";
    }

    // line 67
    public function block_after_translations($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 68
        echo "  ";
        echo do_action("mailpoet_newsletters_translations_after");
        echo "
";
    }

    public function getTemplateName()
    {
        return "newsletters.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  241 => 68,  237 => 67,  229 => 62,  225 => 61,  215 => 53,  211 => 51,  207 => 50,  203 => 49,  199 => 48,  195 => 47,  191 => 46,  187 => 45,  183 => 44,  179 => 43,  175 => 42,  170 => 40,  166 => 39,  162 => 38,  158 => 37,  153 => 35,  149 => 34,  145 => 33,  141 => 32,  136 => 30,  132 => 29,  127 => 27,  122 => 25,  118 => 24,  113 => 22,  109 => 21,  105 => 20,  101 => 19,  97 => 18,  93 => 17,  89 => 16,  85 => 15,  81 => 14,  77 => 13,  73 => 12,  69 => 11,  65 => 10,  61 => 9,  56 => 8,  51 => 4,  47 => 3,  36 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "newsletters.html", "/home/circleci/mailpoet/mailpoet/views/newsletters.html");
    }
}
