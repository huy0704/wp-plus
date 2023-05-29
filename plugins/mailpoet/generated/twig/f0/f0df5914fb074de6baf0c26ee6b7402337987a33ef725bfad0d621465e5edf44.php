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

/* upgrade.html */
class __TwigTemplate_dada0e5bfec8608eb9f123444f9396788d2220a0663c5d2c4a893ecbc6a06709 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'content' => [$this, 'block_content'],
            'after_javascript' => [$this, 'block_after_javascript'],
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
        $this->parent = $this->loadTemplate("layout.html", "upgrade.html", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 4
        echo "<div class=\"mailpoet-premium-page\">
  <div class=\"mailpoet-premium-page-intro mailpoet-premium-page-section mailpoet-grid-two-columns\">
    <div>
      <div class=\"mailpoet-gap-large\"></div>
      <h1 class=\"mailpoet-h1\">
        ";
        // line 9
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Take your email marketing strategy<br> to the next level", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
      </h1>
      <p>
        ";
        // line 12
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Ready to level up your email marketing efforts?", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
        ";
        // line 13
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Upgrade your plan to unlock MailPoet’s <b>advanced features</b>, access to <b>detailed analytics</b>, and <b>priority support</b>.", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
      </p>
      <div class=\"mailpoet-premium-page-intro-link-wrap\">
        <a
          target=\"_blank\"
          ";
        // line 18
        if (($context["has_valid_api_key"] ?? null)) {
            // line 19
            echo "            href=\"";
            echo \MailPoetVendor\twig_escape_filter($this->env, (("https://account.mailpoet.com/orders/upgrade/" . ($context["plugin_partial_key"] ?? null)) . "?utm_source=plugin&utm_medium=premium&utm_campaign=upgrade"), "html", null, true);
            echo "\"
          ";
        } else {
            // line 21
            echo "            href=\"";
            echo \MailPoetVendor\twig_escape_filter($this->env, (((("https://account.mailpoet.com/?s=" . ($context["subscriber_count"] ?? null)) . "&g=business&billing=monthly&email=") . \MailPoetVendor\twig_get_attribute($this->env, $this->source, ($context["current_wp_user"] ?? null), "user_email", [], "any", false, false, false, 21)) . "&utm_source=plugin&utm_medium=premium&utm_campaign=purchase"), "html", null, true);
            echo "\"
          ";
        }
        // line 23
        echo "          class=\"mailpoet-button button-primary\"
        >

          ";
        // line 26
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Upgrade", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
        </a>
      </div>
      <div class=\"mailpoet-gap-large\"></div>
    </div>
    <img
      src=\"";
        // line 32
        echo $this->extensions['MailPoet\Twig\Assets']->generateCdnUrl("premium/1-we-take-care.svg");
        echo "\"
      alt=\"";
        // line 33
        echo $this->extensions['MailPoet\Twig\I18n']->translate("Image bird feeder");
        echo "\"
    >
  </div>

  <hr>

  ";
        // line 39
        if (((($context["subscriber_count"] ?? null) < 1000) &&  !($context["has_valid_api_key"] ?? null))) {
            // line 40
            echo "  <div class=\"mailpoet-premium-page-section mailpoet-premium-page-section-narrow mailpoet-premium-page-text-center\">
    <h1 class=\"mailpoet-h0\">
      ";
            // line 42
            echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("MailPoet Starter plan is actually yours for free", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
            echo "
    </h1>
    <p class=\"mailpoet-premium-page-section-large\">
      ";
            // line 45
            echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Website owners with less than 1,000 subscribers, like you, can get their emails delivered reliably for free with MailPoet Sending Service", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
            echo "
    </p>
    <a
      target=\"_blank\"
      href=\"";
            // line 49
            echo \MailPoetVendor\twig_escape_filter($this->env, (((("https://account.mailpoet.com/?s=" . ($context["subscriber_count"] ?? null)) . "&g=starter&billing=monthly&email=") . \MailPoetVendor\twig_get_attribute($this->env, $this->source, ($context["current_wp_user"] ?? null), "user_email", [], "any", false, false, false, 49)) . \MailPoetVendor\twig_escape_filter($this->env, "&utm_source=plugin&utm_medium=premium&utm_campaign=purchase", "html_attr")), "html", null, true);
            echo "\"
      class=\"mailpoet-button button-primary\"
    >
      ";
            // line 52
            echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Sign up for free", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
            echo "
    </a>
  </div>

  <hr>
  ";
        }
        // line 58
        echo "
  <div class=\"mailpoet-premium-page-upgrade-to-premium mailpoet-premium-page-section\">
    <div class=\" mailpoet-premium-page-section-narrow mailpoet-premium-page-text-center\">
      <h1 class=\"mailpoet-h0\">
        ";
        // line 62
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Fuel growth with<br>a MailPoet Business plan", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
      </h1>
      <p>
        ";
        // line 65
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Everything you need to take your email marketing (and business!) to the next level.", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
      </p>
    </div>
    <div class=\"mailpoet-grid-three-columns mailpoet-premium-three-columns-two-orphans\">
      <div>
        <h2 class=\"mailpoet-h2\">
          ";
        // line 71
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Optimize with detailed engagement statistics", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
        </h2>
        <p>
          ";
        // line 74
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Learn more about your subscribers and optimize your campaigns.", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
          ";
        // line 75
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("<b>Understand how your subscribers engage with your emails</b> via open, click, and unsubscribe statistics.", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
          ";
        // line 76
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("And if you manage a WooCommerce store, you’ll be able to see <b>how your email campaigns influence purchase behavior, including total revenue, number of orders, and the products purchased.</b>", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
        </p>
        <p>
          ";
        // line 79
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Use these valuable insights to make incremental improvements to your email campaigns, and better tailor your marketing to your target audience to <b>increase engagement and conversions!</b>", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
        </p>
      </div>
      <div>
        <h2 class=\"mailpoet-h2\">
          ";
        // line 84
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Get personal with multi-segment criteria", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
        </h2>
        <p>
          ";
        // line 87
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("The more you can tailor your email campaigns to your subscribers’ interests or behavior, the higher the engagement and conversion levels you can expect to see.", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
          ";
        // line 88
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("When you upgrade to a paid plan, you’ll <b>unlock the ability to combine subscriber segments</b> in order to further personalize your email campaigns.", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
        </p>
        <p>
          ";
        // line 91
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("For example, if you’d like to invite your most loyal, long-term subscribers to sign up to receive exclusive content, you could combine the “subscribe date” option with “number of email opens” to send an email to this subset of subscribers inviting them to sign up.", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
          ";
        // line 92
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Or if you run a WooCommerce store and you’d like to encourage one-time customers to return and make an additional purchase, you could combine the “number of orders” segment with “purchased in this category” to send an exclusive discount tailored to their purchase history.", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
        </p>
      </div>
      <div>
        <h2 class=\"mailpoet-h2\">
          ";
        // line 97
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Track engagement with Google Analytics", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
        </h2>
        <p>
          ";
        // line 100
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("<b>Discover the part your email marketing campaigns play in your overall marketing strategy</b> by connecting MailPoet with your Google Analytics account.", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
          ";
        // line 101
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Add a custom campaign name for each of your email campaigns, then <b>track how your subscribers are engaging with your website content</b> (through behavior metrics such as time on site, pages per visit, etc.), where they’re based (filter by city, country, or continent), and how they’re viewing your content (screen resolution, device, operating system, etc.).", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
        </p>
        <p>
          ";
        // line 104
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Unlike in MailPoet, you won’t be able to track the identity of each visitor. This is against Google’s policy, which we respect.", "This text resides in the Uprade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
        </p>
      </div>
      <div>
        <h2 class=\"mailpoet-h2\">
          ";
        // line 109
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Expert help on hand, whenever you need it", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
        </h2>
        <p>
          ";
        // line 112
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("If you’re ever not sure on how to use any of our features, what the best way to achieve your email marketing goals would be, or you run into a technical issue, you’ll have <b>direct access to our expert customer support team</b>.", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
        </p>
        <p>
          ";
        // line 115
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Reach out to them via our priority support form, or by using the chat icon on your dashboard, and one of our friendly experts will respond within 48 hours.", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
        </p>

      </div>
      <div>
        <h2 class=\"mailpoet-h2\">
          ";
        // line 121
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Email that scales with you", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
        </h2>
        <p>
          ";
        // line 124
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Whether you send 10 or 100,000 emails a day, if you use the MailPoet Sending Service, <b>your emails will always be delivered on time, every time</b>.", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
          ";
        // line 125
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Our advanced email infrastructure routinely handles small business newsletters through to huge mailing lists with tens of thousands of subscribers.", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
        </p>
        <p>
          ";
        // line 128
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Our plans scale as your list grows, with an automated upgrade option available for up to 200,000 subscribers.", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
          ";
        // line 129
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Going beyond 200,000? No problem. Contact our friendly support team to discuss plan options and pricing.", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
        </p>
      </div>
    </div>
  </div>

  <hr>

  <div class=\"mailpoet-premium-page-get-started mailpoet-premium-page-section mailpoet-premium-page-text-center\">
    <h1 class=\"mailpoet-h0\">
      ";
        // line 139
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Upgrade and unlock MailPoet’s advanced features", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
    </h1>
    <p>
      ";
        // line 142
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Choose the MailPoet plan that’s right for you.", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
    </p>
    <div class=\"mailpoet-premium-page-options mailpoet-grid-two-columns\">
      <div>
        <div class=\"mailpoet-premium-page-options-label-wrap\">
          <div class=\"mailpoet-premium-page-options-label\">
            ";
        // line 148
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Recommended", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
          </div>
        </div>
        <h1 class=\"mailpoet-h0\">
          ";
        // line 152
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Business", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
        </h1>
        <p class=\"mailpoet-premium-page-text-large\">
          ";
        // line 155
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("From \$10/month,<br>based on 500 subscribers", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
        </p>
        <h3>
          ";
        // line 158
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Send with: MailPoet Sending Service", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
        </h3>
        <div class=\"mailpoet-premium-page-options-divider\"></div>
        <h3 class=\"mailpoet-premium-feature-list-heading\">
          ";
        // line 162
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("This plan includes:", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
        </h3>
        <ul>
          <li>";
        // line 165
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Use MailPoet on 1 website", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "</li>
          <li>";
        // line 166
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Send unlimited emails", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "</li>
          <li>";
        // line 167
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Without MailPoet branding", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "</li>
          <li>";
        // line 168
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Priority customer support", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "</li>
        </ul>
        <h3 class=\"mailpoet-premium-feature-list-heading\">
          ";
        // line 171
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("All Starter features, plus:", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
        </h3>
        <ul>
          <li>";
        // line 174
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Detailed engagement statistics", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "</li>
          <li>";
        // line 175
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Subscriber segmentation", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "</li>
          <li>";
        // line 176
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Google Analytics integration", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "</li>
        </ul>
        <a
          target=\"_blank\"
          ";
        // line 180
        if (($context["has_valid_api_key"] ?? null)) {
            // line 181
            echo "            href=\"";
            echo \MailPoetVendor\twig_escape_filter($this->env, (("https://account.mailpoet.com/orders/upgrade/" . ($context["plugin_partial_key"] ?? null)) . "?utm_source=plugin&utm_medium=premium&utm_campaign=upgrade"), "html", null, true);
            echo "\"
          ";
        } else {
            // line 183
            echo "            href=\"";
            echo \MailPoetVendor\twig_escape_filter($this->env, (((("https://account.mailpoet.com/?s=" . ($context["subscriber_count"] ?? null)) . "&g=business&billing=monthly&email=") . \MailPoetVendor\twig_get_attribute($this->env, $this->source, ($context["current_wp_user"] ?? null), "user_email", [], "any", false, false, false, 183)) . "&utm_source=plugin&utm_medium=premium&utm_campaign=purchase"), "html", null, true);
            echo "\"
          ";
        }
        // line 185
        echo "        class=\"mailpoet-button button-primary\"
        >
          ";
        // line 187
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Get started", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
        </a>
      </div>
      <div>
        <div class=\"mailpoet-premium-page-options-label-wrap\"></div>
        <h1 class=\"mailpoet-h0\">
          ";
        // line 193
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Creator", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
        </h1>
        <p class=\"mailpoet-premium-page-text-large\">
          ";
        // line 196
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("From \$8/month,<br>based on 500 subscribers", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
        </p>
        <h3>
          ";
        // line 199
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Send with: Your own sending method", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
        </h3>
        <h3 class=\"mailpoet-premium-feature-list-heading\">
          ";
        // line 202
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("This plan includes:", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
        </h3>
        <ul>
          <li>";
        // line 205
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Use MailPoet on 1 website", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "</li>
          <li>";
        // line 206
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Without MailPoet branding", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "</li>
          <li>";
        // line 207
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Priority customer support", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "</li>
        </ul>
        <h3 class=\"mailpoet-premium-feature-list-heading\">
          ";
        // line 210
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("All Starter features, plus:", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
        </h3>
        <ul>
          <li>";
        // line 213
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Detailed engagement statistics", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "</li>
          <li>";
        // line 214
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Subscriber segmentation", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "</li>
          <li>";
        // line 215
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Google Analytics integration", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "</li>
        </ul>
        <div class=\"mailpoet-premium-page-options-divider\"></div>
        <a
          target=\"_blank\"
          ";
        // line 220
        if (($context["has_valid_api_key"] ?? null)) {
            // line 221
            echo "            href=\"";
            echo \MailPoetVendor\twig_escape_filter($this->env, (("https://account.mailpoet.com/orders/upgrade/" . ($context["plugin_partial_key"] ?? null)) . "?utm_source=plugin&utm_medium=premium&utm_campaign=upgrade"), "html", null, true);
            echo "\"
          ";
        } else {
            // line 223
            echo "            href=\"";
            echo \MailPoetVendor\twig_escape_filter($this->env, (((("https://account.mailpoet.com/?s=" . ($context["subscriber_count"] ?? null)) . "&g=creator&billing=monthly&email=") . \MailPoetVendor\twig_get_attribute($this->env, $this->source, ($context["current_wp_user"] ?? null), "user_email", [], "any", false, false, false, 223)) . "&utm_source=plugin&utm_medium=premium&utm_campaign=purchase"), "html", null, true);
            echo "\"
          ";
        }
        // line 225
        echo "          class=\"mailpoet-button button-primary\"
        >
          ";
        // line 227
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Get started", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
        </a>
      </div>
    </div>
  </div>

  <hr>

  <div class=\"mailpoet-premium-page-our-agency-license mailpoet-premium-page-section mailpoet-grid-two-columns\">
    <div>
      <h1 class=\"mailpoet-h0\">
        ";
        // line 238
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Want to use MailPoet on multiple websites? Choose the Agency plan", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
      </h1>
      <p>
        ";
        // line 241
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Our Agency plan has been designed for those who maintain or build WordPress and WooCommerce solutions for multiple clients.", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
        ";
        // line 242
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Quickly deploy an easy to learn email solution on up to 50 websites, and rest assured that your clients’ emails make it to the inbox with the MailPoet Sending Service.", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
      </p>
      <h3>
        ";
        // line 245
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("From \$30/month, based on 500 subscribers per website", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
      </h3>
      <h3>
        ";
        // line 248
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Send with: MailPoet Sending Service", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
      </h3>
      <h3>
        ";
        // line 251
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("This plan includes:", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
      </h3>
      <ul class=\"mailpoet-premium-page-bullet-list\">
        <li>";
        // line 254
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Use MailPoet on 50 websites", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "</li>
        <li>";
        // line 255
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Send unlimited emails", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "</li>
        <li>";
        // line 256
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Without MailPoet branding", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "</li>
        <li>";
        // line 257
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Priority customer support", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "</li>
      </ul>
      <h3>
        ";
        // line 260
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("All Starter features, plus:", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
      </h3>
      <ul class=\"mailpoet-premium-page-bullet-list\">
        <li>";
        // line 263
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Detailed engagement statistics", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "</li>
        <li>";
        // line 264
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Subscriber segmentation", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "</li>
        <li>";
        // line 265
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Google Analytics integration", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "</li>
      </ul>
      <a
        target=\"_blank\"
        ";
        // line 269
        if (($context["has_valid_api_key"] ?? null)) {
            // line 270
            echo "          href=\"";
            echo \MailPoetVendor\twig_escape_filter($this->env, (("https://account.mailpoet.com/orders/upgrade/" . ($context["plugin_partial_key"] ?? null)) . "?utm_source=plugin&utm_medium=premium&utm_campaign=upgrade"), "html", null, true);
            echo "\"
        ";
        } else {
            // line 272
            echo "          href=\"";
            echo \MailPoetVendor\twig_escape_filter($this->env, (((("https://account.mailpoet.com/?s=" . ($context["subscriber_count"] ?? null)) . "&g=agency&billing=monthly&email=") . \MailPoetVendor\twig_get_attribute($this->env, $this->source, ($context["current_wp_user"] ?? null), "user_email", [], "any", false, false, false, 272)) . "&utm_source=plugin&utm_medium=premium&utm_campaign=purchase"), "html", null, true);
            echo "\"
        ";
        }
        // line 274
        echo "        class=\"mailpoet-button button-primary\"
      >
        ";
        // line 276
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Buy Now", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
      </a>
    </div>
    <img
      src=\"";
        // line 280
        echo $this->extensions['MailPoet\Twig\Assets']->generateCdnUrl("premium/3-our-agency-license.svg");
        echo "\"
      alt=\"";
        // line 281
        echo $this->extensions['MailPoet\Twig\I18n']->translate("Image woodpecker");
        echo "\"
    >
  </div>

  <hr>

  <div class=\"mailpoet-premium-page-section mailpoet-premium-page-section-narrow mailpoet-premium-page-text-center\">
    <h1 class=\"mailpoet-h0\">
      ";
        // line 289
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Get started today!", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
    </h1>
    <p class=\"mailpoet-premium-page-text-large\">
      ";
        // line 292
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Over 500,000 people trust MailPoet to power their email marketing campaigns – why not join them?!", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
      ";
        // line 293
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Upgrade now to grow your business and achieve your business goals.", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
    </p>
    <a
      target=\"_blank\"
      ";
        // line 297
        if (($context["has_valid_api_key"] ?? null)) {
            // line 298
            echo "        href=\"";
            echo \MailPoetVendor\twig_escape_filter($this->env, (("https://account.mailpoet.com/orders/upgrade/" . ($context["plugin_partial_key"] ?? null)) . "?utm_source=plugin&utm_medium=premium&utm_campaign=upgrade"), "html", null, true);
            echo "\"
      ";
        } else {
            // line 300
            echo "        href=\"";
            echo \MailPoetVendor\twig_escape_filter($this->env, (((("https://account.mailpoet.com/?s=" . ($context["subscriber_count"] ?? null)) . "&g=business&billing=monthly&email=") . \MailPoetVendor\twig_get_attribute($this->env, $this->source, ($context["current_wp_user"] ?? null), "user_email", [], "any", false, false, false, 300)) . "&utm_source=plugin&utm_medium=premium&utm_campaign=purchase"), "html", null, true);
            echo "\"
      ";
        }
        // line 302
        echo "      class=\"mailpoet-button button-primary\"
    >
      ";
        // line 304
        echo $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Upgrade now", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade");
        echo "
    </a>
    <p>
      ";
        // line 307
        echo MailPoet\Util\Helpers::replaceLinkTags($this->extensions['MailPoet\Twig\I18n']->translateWithContext("And if you’re not sure which plan is the right one for you, our [link]Customer Support team[/link] are on hand to help you decide.", "This text resides in the Upgrade page: /wp-admin/admin.php?page=mailpoet-upgrade"), "https://www.mailpoet.com/support/sales-pre-sales-questions/", ["target" => "_blank"]);
        // line 311
        echo "
    </p>
  </div>
</div>

<div class=\"mailpoet-premium-page-footer-image\">
  <img
    src=\"";
        // line 318
        echo $this->extensions['MailPoet\Twig\Assets']->generateCdnUrl("premium/5-footer.png");
        echo "\"
    alt=\"";
        // line 319
        echo $this->extensions['MailPoet\Twig\I18n']->translate("Image rooster crowing");
        echo "\"
  >
</div>
";
    }

    // line 324
    public function block_after_javascript($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 325
        echo "<script type=\"text/javascript\">
  MailPoet.trackEvent('Premium page viewed');
</script>
";
    }

    public function getTemplateName()
    {
        return "upgrade.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  692 => 325,  688 => 324,  680 => 319,  676 => 318,  667 => 311,  665 => 307,  659 => 304,  655 => 302,  649 => 300,  643 => 298,  641 => 297,  634 => 293,  630 => 292,  624 => 289,  613 => 281,  609 => 280,  602 => 276,  598 => 274,  592 => 272,  586 => 270,  584 => 269,  577 => 265,  573 => 264,  569 => 263,  563 => 260,  557 => 257,  553 => 256,  549 => 255,  545 => 254,  539 => 251,  533 => 248,  527 => 245,  521 => 242,  517 => 241,  511 => 238,  497 => 227,  493 => 225,  487 => 223,  481 => 221,  479 => 220,  471 => 215,  467 => 214,  463 => 213,  457 => 210,  451 => 207,  447 => 206,  443 => 205,  437 => 202,  431 => 199,  425 => 196,  419 => 193,  410 => 187,  406 => 185,  400 => 183,  394 => 181,  392 => 180,  385 => 176,  381 => 175,  377 => 174,  371 => 171,  365 => 168,  361 => 167,  357 => 166,  353 => 165,  347 => 162,  340 => 158,  334 => 155,  328 => 152,  321 => 148,  312 => 142,  306 => 139,  293 => 129,  289 => 128,  283 => 125,  279 => 124,  273 => 121,  264 => 115,  258 => 112,  252 => 109,  244 => 104,  238 => 101,  234 => 100,  228 => 97,  220 => 92,  216 => 91,  210 => 88,  206 => 87,  200 => 84,  192 => 79,  186 => 76,  182 => 75,  178 => 74,  172 => 71,  163 => 65,  157 => 62,  151 => 58,  142 => 52,  136 => 49,  129 => 45,  123 => 42,  119 => 40,  117 => 39,  108 => 33,  104 => 32,  95 => 26,  90 => 23,  84 => 21,  78 => 19,  76 => 18,  68 => 13,  64 => 12,  58 => 9,  51 => 4,  47 => 3,  36 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "upgrade.html", "/home/circleci/mailpoet/mailpoet/views/upgrade.html");
    }
}
