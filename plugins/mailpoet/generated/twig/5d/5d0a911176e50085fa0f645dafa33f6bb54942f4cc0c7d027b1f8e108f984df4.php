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

/* layout.html */
class __TwigTemplate_8581a8ede6c645c86df82d14a0f9727da4ff92aa0bb85d1970f9d5219782671e extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
            'templates' => [$this, 'block_templates'],
            'container' => [$this, 'block_container'],
            'title' => [$this, 'block_title'],
            'content' => [$this, 'block_content'],
            'after_css' => [$this, 'block_after_css'],
            'translations' => [$this, 'block_translations'],
            'after_translations' => [$this, 'block_after_translations'],
            'after_javascript' => [$this, 'block_after_javascript'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        if (($context["sub_menu"] ?? null)) {
            // line 2
            echo "<script type=\"text/javascript\">
jQuery('#adminmenu #toplevel_page_mailpoet-newsletters')
  .addClass('wp-has-current-submenu')
  .removeClass('wp-not-current-submenu')
  .find('a[href\$=\"";
            // line 6
            echo \MailPoetVendor\twig_escape_filter($this->env, ($context["sub_menu"] ?? null), "html", null, true);
            echo "\"]')
  .addClass('current')
  .parent()
  .addClass('current');
</script>
";
        }
        // line 12
        echo "
<!-- pre connect to 3d party to speed up page loading -->
<link rel=\"preconnect\" href=\"https://beacon-v2.helpscout.net/\">
<link rel=\"dns-prefetch\" href=\"https://beacon-v2.helpscout.net/\">
<link rel=\"preconnect\" href=\"http://cdn.mxpnl.com\">
<link rel=\"dns-prefetch\" href=\"http://cdn.mxpnl.com\">

<!-- system notices -->
<div id=\"mailpoet_notice_system\" class=\"mailpoet_notice\" style=\"display:none;\"></div>

<!-- handlebars templates -->
";
        // line 23
        $this->displayBlock('templates', $context, $blocks);
        // line 24
        echo "
<!-- main container -->
";
        // line 26
        $this->displayBlock('container', $context, $blocks);
        // line 47
        echo "
";
        // line 48
        echo do_action("mailpoet_styles_admin_after");
        echo "

";
        // line 50
        $this->displayBlock('after_css', $context, $blocks);
        // line 51
        echo "
<script type=\"text/javascript\">
  var mailpoet_datetime_format = \"";
        // line 53
        echo \MailPoetVendor\twig_escape_filter($this->env, \MailPoetVendor\twig_escape_filter($this->env, $this->extensions['MailPoet\Twig\Functions']->getWPDateTimeFormat(), "js"), "html", null, true);
        echo "\";
  var mailpoet_date_format = \"";
        // line 54
        echo \MailPoetVendor\twig_escape_filter($this->env, \MailPoetVendor\twig_escape_filter($this->env, $this->extensions['MailPoet\Twig\Functions']->getWPDateFormat(), "js"), "html", null, true);
        echo "\";
  var mailpoet_time_format = \"";
        // line 55
        echo \MailPoetVendor\twig_escape_filter($this->env, \MailPoetVendor\twig_escape_filter($this->env, $this->extensions['MailPoet\Twig\Functions']->getWPTimeFormat(), "js"), "html", null, true);
        echo "\";
  var mailpoet_version = \"";
        // line 56
        echo $this->extensions['MailPoet\Twig\Functions']->getMailPoetVersion();
        echo "\";
  var mailpoet_locale = \"";
        // line 57
        echo $this->extensions['MailPoet\Twig\Functions']->getTwoLettersLocale();
        echo "\";
  var mailpoet_wp_week_starts_on = \"";
        // line 58
        echo $this->extensions['MailPoet\Twig\Functions']->getWPStartOfWeek();
        echo "\";
  var mailpoet_urls = ";
        // line 59
        echo json_encode(($context["urls"] ?? null));
        echo ";
  var mailpoet_premium_version = ";
        // line 60
        echo json_encode($this->extensions['MailPoet\Twig\Functions']->getMailPoetPremiumVersion());
        echo ";
  var mailpoet_main_page_slug =   ";
        // line 61
        echo json_encode(($context["main_page"] ?? null));
        echo ";
  var mailpoet_3rd_party_libs_enabled = ";
        // line 62
        echo \MailPoetVendor\twig_escape_filter($this->env, json_encode($this->extensions['MailPoet\Twig\Functions']->libs3rdPartyEnabled()), "html", null, true);
        echo ";
  var mailpoet_analytics_enabled = ";
        // line 63
        echo \MailPoetVendor\twig_escape_filter($this->env, json_encode($this->extensions['MailPoet\Twig\Analytics']->isEnabled()), "html", null, true);
        echo ";
  var mailpoet_analytics_data = ";
        // line 64
        echo json_encode($this->extensions['MailPoet\Twig\Analytics']->generateAnalytics());
        echo ";
  var mailpoet_analytics_public_id = ";
        // line 65
        echo json_encode($this->extensions['MailPoet\Twig\Analytics']->getPublicId());
        echo ";
  var mailpoet_analytics_new_public_id = ";
        // line 66
        echo \MailPoetVendor\twig_escape_filter($this->env, json_encode($this->extensions['MailPoet\Twig\Analytics']->isPublicIdNew()), "html", null, true);
        echo ";
  var mailpoet_free_domains = ";
        // line 67
        echo json_encode($this->extensions['MailPoet\Twig\Functions']->getFreeDomains());
        echo ";
  var mailpoet_woocommerce_active = ";
        // line 68
        echo json_encode($this->extensions['MailPoet\Twig\Functions']->isWoocommerceActive());
        echo ";
  // RFC 5322 standard; http://emailregex.com/ combined with https://google.github.io/closure-library/api/goog.format.EmailAddress.html#isValid
  var mailpoet_email_regex = /(?=^[+a-zA-Z0-9_.!#\$%&'*\\/=?^`{|}~-]+@([a-zA-Z0-9-]+\\.)+[a-zA-Z0-9]{2,63}\$)(?=^(([^<>()\\[\\]\\\\.,;:\\s@\"]+(\\.[^<>()\\[\\]\\\\.,;:\\s@\"]+)*)|(\".+\"))@((\\[[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}])|(([a-zA-Z\\-0-9]+\\.)+[a-zA-Z]{2,})))/;
  var mailpoet_feature_flags = ";
        // line 71
        echo json_encode(($context["feature_flags"] ?? null));
        echo ";
  var mailpoet_referral_id = ";
        // line 72
        echo json_encode(($context["referral_id"] ?? null));
        echo ";
  var mailpoet_feature_announcement_has_news = ";
        // line 73
        echo json_encode(($context["feature_announcement_has_news"] ?? null));
        echo ";
  var mailpoet_wp_segment_state = ";
        // line 74
        echo json_encode(($context["wp_segment_state"] ?? null));
        echo ";
  var mailpoet_mta_method = '";
        // line 75
        echo \MailPoetVendor\twig_escape_filter($this->env, ($context["mta_method"] ?? null), "html", null, true);
        echo "';
  var mailpoet_tracking_config = ";
        // line 76
        echo json_encode(($context["tracking_config"] ?? null));
        echo ";
  var mailpoet_is_new_user = ";
        // line 77
        echo json_encode((($context["is_new_user"] ?? null) == true));
        echo ";
  var mailpoet_installed_days_ago = ";
        // line 78
        echo json_encode(($context["installed_days_ago"] ?? null));
        echo ";
  var mailpoet_send_transactional_emails = ";
        // line 79
        echo json_encode(($context["send_transactional_emails"] ?? null));
        echo ";
  var mailpoet_transactional_emails_opt_in_notice_dismissed = ";
        // line 80
        echo json_encode(($context["transactional_emails_opt_in_notice_dismissed"] ?? null));
        echo ";
  var mailpoet_deactivate_subscriber_after_inactive_days = ";
        // line 81
        echo json_encode(($context["deactivate_subscriber_after_inactive_days"] ?? null));
        echo ";
  var mailpoet_woocommerce_version = ";
        // line 82
        echo json_encode($this->extensions['MailPoet\Twig\Functions']->getWooCommerceVersion());
        echo ";
  var mailpoet_track_wizard_loaded_via_woocommerce = ";
        // line 83
        echo json_encode(($context["track_wizard_loaded_via_woocommerce"] ?? null));
        echo ";
  var mailpoet_mail_function_enabled = '";
        // line 84
        echo \MailPoetVendor\twig_escape_filter($this->env, ($context["mail_function_enabled"] ?? null), "html", null, true);
        echo "';

  var mailpoet_site_name = '";
        // line 86
        echo \MailPoetVendor\twig_escape_filter($this->env, ($context["site_name"] ?? null), "html", null, true);
        echo "';
  var mailpoet_site_url = \"";
        // line 87
        echo \MailPoetVendor\twig_escape_filter($this->env, ($context["site_url"] ?? null), "html", null, true);
        echo "\";
  var mailpoet_site_address = '";
        // line 88
        echo \MailPoetVendor\twig_escape_filter($this->env, ($context["site_address"] ?? null), "html", null, true);
        echo "';

  // Premium status
  var mailpoet_current_wp_user_email = '";
        // line 91
        echo \MailPoetVendor\twig_escape_filter($this->env, \MailPoetVendor\twig_escape_filter($this->env, ($context["current_wp_user_email"] ?? null), "js"), "html", null, true);
        echo "';
  var mailpoet_premium_link = ";
        // line 92
        echo json_encode(($context["link_premium"] ?? null));
        echo ";
  var mailpoet_premium_plugin_installed = ";
        // line 93
        echo json_encode(($context["premium_plugin_installed"] ?? null));
        echo ";
  var mailpoet_premium_active = ";
        // line 94
        echo json_encode(($context["premium_plugin_active"] ?? null));
        echo ";
  var mailpoet_premium_plugin_download_url = ";
        // line 95
        echo json_encode(($context["premium_plugin_download_url"] ?? null));
        echo ";
  var mailpoet_premium_plugin_activation_url = ";
        // line 96
        echo json_encode(($context["premium_plugin_activation_url"] ?? null));
        echo ";
  var mailpoet_has_valid_api_key = ";
        // line 97
        echo json_encode(($context["has_valid_api_key"] ?? null));
        echo ";
  var mailpoet_has_valid_premium_key = ";
        // line 98
        echo json_encode(($context["has_valid_premium_key"] ?? null));
        echo ";
  var mailpoet_has_premium_support = ";
        // line 99
        echo json_encode(($context["has_premium_support"] ?? null));
        echo ";
  var has_mss_key_specified = ";
        // line 100
        echo json_encode(($context["has_mss_key_specified"] ?? null));
        echo ";
  var mailpoet_mss_key_invalid = ";
        // line 101
        echo json_encode(($context["mss_key_invalid"] ?? null));
        echo ";
  var mailpoet_mss_key_pending_approval = '";
        // line 102
        echo \MailPoetVendor\twig_escape_filter($this->env, ($context["mss_key_pending_approval"] ?? null), "html", null, true);
        echo "';
  var mailpoet_mss_active = ";
        // line 103
        echo json_encode(($context["mss_active"] ?? null));
        echo ";
  var mailpoet_plugin_partial_key = '";
        // line 104
        echo \MailPoetVendor\twig_escape_filter($this->env, ($context["plugin_partial_key"] ?? null), "html", null, true);
        echo "';
  var mailpoet_subscribers_count = ";
        // line 105
        echo \MailPoetVendor\twig_escape_filter($this->env, ($context["subscriber_count"] ?? null), "html", null, true);
        echo ";
  var mailpoet_subscribers_counts_cache_created_at = ";
        // line 106
        echo json_encode(($context["subscribers_counts_cache_created_at"] ?? null));
        echo ";
  var mailpoet_subscribers_limit = ";
        // line 107
        ((($context["subscribers_limit"] ?? null)) ? (print (\MailPoetVendor\twig_escape_filter($this->env, ($context["subscribers_limit"] ?? null), "html", null, true))) : (print ("false")));
        echo ";
  var mailpoet_subscribers_limit_reached = ";
        // line 108
        echo json_encode(($context["subscribers_limit_reached"] ?? null));
        echo ";
  var mailpoet_email_volume_limit = ";
        // line 109
        echo json_encode(($context["email_volume_limit"] ?? null));
        echo ";
  var mailpoet_email_volume_limit_reached = ";
        // line 110
        echo json_encode(($context["email_volume_limit_reached"] ?? null));
        echo ";
  var mailpoet_cdn_url = ";
        // line 111
        echo json_encode($this->extensions['MailPoet\Twig\Assets']->generateCdnUrl(""));
        echo ";
  var mailpoet_tags = ";
        // line 112
        echo json_encode(($context["tags"] ?? null));
        echo ";

  ";
        // line 114
        if ( !($context["premium_plugin_active"] ?? null)) {
            // line 115
            echo "    var mailpoet_free_premium_subscribers_limit = ";
            echo \MailPoetVendor\twig_escape_filter($this->env, ($context["free_premium_subscribers_limit"] ?? null), "html", null, true);
            echo ";
  ";
        }
        // line 117
        echo "</script>

<!-- javascripts -->
";
        // line 120
        echo $this->extensions['MailPoet\Twig\Assets']->generateJavascript("runtime.js", "vendor.js", "mailpoet.js");
        // line 124
        echo "

";
        // line 126
        echo $this->extensions['MailPoet\Twig\I18n']->localize(["topBarLogoTitle" => $this->extensions['MailPoet\Twig\I18n']->translate("Back to section root"), "topBarUpdates" => $this->extensions['MailPoet\Twig\I18n']->translate("Updates"), "whatsNew" => $this->extensions['MailPoet\Twig\I18n']->translate("What’s new"), "updateMailPoetNotice" => $this->extensions['MailPoet\Twig\I18n']->translate("[link]Update MailPoet[/link] to see the latest changes"), "ajaxFailedErrorMessage" => $this->extensions['MailPoet\Twig\I18n']->translate("An error has happened while performing a request, the server has responded with response code %d"), "ajaxTimeoutErrorMessage" => $this->extensions['MailPoet\Twig\I18n']->translate("An error has happened while performing a request, the server request has timed out after %d seconds"), "senderEmailAddressWarning1" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("You might not reach the inbox of your subscribers if you use this email address.", "In the last step, before sending a newsletter. URL: ?page=mailpoet-newsletters#/send/2"), "senderEmailAddressWarning3" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Read more."), "mailerSendingNotResumedUnauthorized" => $this->extensions['MailPoet\Twig\I18n']->translate("Failed to resume sending because the email address is unauthorized. Please authorize it and try again."), "dismissNotice" => $this->extensions['MailPoet\Twig\I18n']->translate("Dismiss this notice."), "subscribersLimitNoticeTitle" => $this->extensions['MailPoet\Twig\I18n']->translate("Congratulations, you now have more than [subscribersLimit] subscribers!"), "freeVersionLimit" => $this->extensions['MailPoet\Twig\I18n']->translate("Our free version is limited to [subscribersLimit] subscribers."), "yourPlanLimit" => $this->extensions['MailPoet\Twig\I18n']->translate("Your plan is limited to [subscribersLimit] subscribers."), "youNeedToUpgrade" => $this->extensions['MailPoet\Twig\I18n']->translate("You need to upgrade now to be able to continue using MailPoet."), "youCanDisableWPUsersList" => $this->extensions['MailPoet\Twig\I18n']->translate("If you do not send emails to your WordPress Users list, you can [link]disable it[/link] to lower your number of subscribers."), "upgradeNow" => $this->extensions['MailPoet\Twig\I18n']->translate("Upgrade Now"), "refreshMySubscribers" => $this->extensions['MailPoet\Twig\I18n']->translate("I’ve upgraded my subscription, refresh subscriber limit"), "emailVolumeLimitNoticeTitle" => $this->extensions['MailPoet\Twig\I18n']->translate("Congratulations, you sent more than [emailVolumeLimit] emails this month!"), "youReachedEmailVolumeLimit" => $this->extensions['MailPoet\Twig\I18n']->translate("You have sent more emails this month than your MailPoet plan includes ([emailVolumeLimit]), and sending has been temporarily paused."), "toContinueUpgradeYourPlanOrWaitUntil" => $this->extensions['MailPoet\Twig\I18n']->translate("To continue sending with MailPoet Sending Service please [link]upgrade your plan[/link], or wait until sending is automatically resumed on <b>[date]</b>."), "refreshMyEmailVolumeLimit" => $this->extensions['MailPoet\Twig\I18n']->translate("I’ve upgraded my subscription, refresh monthly email limit"), "manageSenderDomainHeaderSubtitle" => $this->extensions['MailPoet\Twig\I18n']->translate("To help your audience and MailPoet authenticate you as the domain owner, please add the following DNS records to your domain’s DNS and click “Verify the DNS records”. Please note that it may take up to 24 hours for DNS changes to propagate after you make the change. [link]Read the guide[/link].", "mailpoet"), "reviewRequestHeading" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Thank you! Time to tell the world?", "After a user gives us positive feedback via the NPS poll, we ask them to review our plugin on WordPress.org."), "reviewRequestDidYouKnow" => $this->extensions['MailPoet\Twig\I18n']->translate("[username], did you know that hundreds of WordPress users read the reviews on the plugin repository? They’re also a source of inspiration for our team."), "reviewRequestUsingForDays" => $this->extensions['MailPoet\Twig\I18n']->pluralize("You’ve been using MailPoet for [days] day now, and we would love to read your own review.", "You’ve been using MailPoet for [days] days now, and we would love to read your own review.",         // line 155
($context["installed_days_ago"] ?? null)), "reviewRequestUsingForMonths" => $this->extensions['MailPoet\Twig\I18n']->pluralize("You’ve been using MailPoet for [months] month now, and we would love to read your own review.", "You’ve been using MailPoet for [months] months now, and we would love to read your own review.", \MailPoetVendor\twig_round((        // line 156
($context["installed_days_ago"] ?? null) / 30))), "reviewRequestRateUsNow" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Rate us now", "Review our plugin on WordPress.org."), "reviewRequestNotNow" => $this->extensions['MailPoet\Twig\I18n']->translate("Not now"), "sent" => $this->extensions['MailPoet\Twig\I18n']->translate("Sent"), "notSentYet" => $this->extensions['MailPoet\Twig\I18n']->translate("Not sent yet!"), "renderingProblem" => $this->extensions['MailPoet\Twig\I18n']->translate("There was a problem with rendering!", "mailpoet"), "allSendingPausedHeader" => $this->extensions['MailPoet\Twig\I18n']->translate("All sending is currently paused!"), "allSendingPausedBody" => $this->extensions['MailPoet\Twig\I18n']->translate("Your [link]API key[/link] to send with MailPoet is invalid."), "allSendingPausedLink" => $this->extensions['MailPoet\Twig\I18n']->translate("Purchase a key"), "transactionalEmailNoticeTitle" => $this->extensions['MailPoet\Twig\I18n']->translate("Good news! MailPoet can now send your website’s emails too"), "transactionalEmailNoticeBody" => $this->extensions['MailPoet\Twig\I18n']->translate("All of your WordPress and WooCommerce emails are sent with your hosting company, unless you have an SMTP plugin. Would you like such emails to be delivered with MailPoet’s active sending method for better deliverability?"), "transactionalEmailNoticeBodyReadMore" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Read more.", "This is a link that leads to more information about transactional emails"), "transactionalEmailNoticeCTA" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Enable", "Button, after clicking it we will enable transactional emails"), "mailerSendErrorNotice" => $this->extensions['MailPoet\Twig\I18n']->translate("Sending has been paused due to a technical issue with %1\$s"), "mailerSendErrorCheckConfiguration" => $this->extensions['MailPoet\Twig\I18n']->translate("Please check your sending method configuration, you may need to consult with your hosting company."), "mailerSendErrorUseSendingService" => $this->extensions['MailPoet\Twig\I18n']->translate("The easy alternative is to <b>send emails with MailPoet Sending Service</b> instead, like thousands of other users do."), "mailerSendErrorSignUpForSendingService" => $this->extensions['MailPoet\Twig\I18n']->translate("Sign up for free in minutes"), "mailerConnectionErrorNotice" => $this->extensions['MailPoet\Twig\I18n']->translate("Sending is paused because the following connection issue prevents MailPoet from delivering emails"), "mailerErrorCode" => $this->extensions['MailPoet\Twig\I18n']->translate("Error code: %1\$s"), "mailerCheckSettingsNotice" => $this->extensions['MailPoet\Twig\I18n']->translate("Check your [link]sending method settings[/link]."), "mailerResumeSendingButton" => $this->extensions['MailPoet\Twig\I18n']->translate("Resume sending"), "mailerResumeSendingAfterUpgradeButton" => $this->extensions['MailPoet\Twig\I18n']->translate("I have upgraded my subscription, resume sending"), "topBarLogoTitle" => $this->extensions['MailPoet\Twig\I18n']->translate("Back to section root"), "close" => $this->extensions['MailPoet\Twig\I18n']->translate("Close"), "today" => $this->extensions['MailPoet\Twig\I18n']->translate("Today")]);
        // line 187
        echo "
";
        // line 188
        $this->displayBlock('translations', $context, $blocks);
        // line 189
        echo "
";
        // line 190
        $this->displayBlock('after_translations', $context, $blocks);
        // line 191
        echo $this->extensions['MailPoet\Twig\Assets']->generateJavascript("admin_vendor.js");
        // line 193
        echo "

";
        // line 195
        echo do_action("mailpoet_scripts_admin_before");
        echo "

";
        // line 197
        if (($this->extensions['MailPoet\Twig\Functions']->libs3rdPartyEnabled() &&  !$this->extensions['MailPoet\Twig\Functions']->isDotcomEcommercePlan())) {
            // line 198
            echo "  ";
            echo $this->extensions['MailPoet\Twig\Assets']->generateJavascript("lib/analytics.js");
            echo "

  ";
            // line 200
            $context["helpscout_form_id"] = "1c666cab-c0f6-4614-bc06-e5d0ad78db2b";
            // line 201
            echo "  ";
            if (((\MailPoetVendor\twig_get_attribute($this->env, $this->source, \MailPoetVendor\twig_get_attribute($this->env, $this->source, ($context["mailpoet_api_key_state"] ?? null), "data", [], "any", false, false, false, 201), "support_tier", [], "any", false, false, false, 201) == "premium") || (\MailPoetVendor\twig_get_attribute($this->env, $this->source, \MailPoetVendor\twig_get_attribute($this->env, $this->source, ($context["premium_key_state"] ?? null), "data", [], "any", false, false, false, 201), "support_tier", [], "any", false, false, false, 201) == "premium"))) {
                // line 202
                echo "    ";
                $context["helpscout_form_id"] = "e93d0423-1fa6-4bbc-9df9-c174f823c35f";
                // line 203
                echo "  ";
            }
            // line 204
            echo "  ";
            if ((((($context["current_page"] ?? null) == "mailpoet-automation") || (($context["current_page"] ?? null) == "mailpoet-automation-editor")) || (($context["current_page"] ?? null) == "mailpoet-automation-templates"))) {
                // line 205
                echo "    ";
                $context["helpscout_form_id"] = "69b66c7a-e9e9-4544-9d2a-5eb0ddc74959";
                // line 206
                echo "  ";
            }
            // line 207
            echo "
  <script type=\"text/javascript\">!function(e,t,n){function a(){var e=t.getElementsByTagName(\"script\")[0],n=t.createElement(\"script\");n.type=\"text/javascript\",n.async=!0,n.src=\"https://beacon-v2.helpscout.net\",e.parentNode.insertBefore(n,e)}if(e.Beacon=n=function(t,n,a){e.Beacon.readyQueue.push({method:t,options:n,data:a})},n.readyQueue=[],\"complete\"===t.readyState)return a();e.attachEvent?e.attachEvent(\"onload\",a):e.addEventListener(\"load\",a,!1)}(window,document,window.Beacon||function(){});</script>

  <script type=\"text/javascript\">
    if(window['Beacon'] !== undefined && window.hide_mailpoet_beacon !== true) {
      window.Beacon('init', '";
            // line 212
            echo \MailPoetVendor\twig_escape_filter($this->env, ($context["helpscout_form_id"] ?? null), "html", null, true);
            echo "');

      // HelpScout Beacon: Configuration
      window.Beacon(\"config\", {
        icon: 'message',
        zIndex: 50000,
        instructions: \"";
            // line 218
            echo $this->extensions['MailPoet\Twig\I18n']->translate("Want to give feedback to the MailPoet team? Contact us here. Please provide as much information as possible!");
            echo "\",
        showContactFields: true
      });

      // HelpScout Beacon: User identity information
      window.Beacon(\"identify\",
        ";
            // line 224
            echo json_encode($this->extensions['MailPoet\Twig\Helpscout']->getHelpscoutUserData());
            echo "
      );

      // HelpScout Beacon: Custom information
      window.Beacon(\"session-data\",
        ";
            // line 229
            echo json_encode($this->extensions['MailPoet\Twig\Helpscout']->getHelpscoutSiteData());
            echo "
    );

      if (window.mailpoet_beacon_articles) {
        window.Beacon('suggest', window.mailpoet_beacon_articles)
      }
    }
  </script>
";
        }
        // line 238
        echo "
<script>
  Parsley.addMessages('mailpoet', {
    defaultMessage: '";
        // line 241
        echo $this->extensions['MailPoet\Twig\I18n']->translate("This value seems to be invalid.");
        echo "',
    type: {
      email: '";
        // line 243
        echo $this->extensions['MailPoet\Twig\I18n']->translate("This value should be a valid email.");
        echo "',
      url: '";
        // line 244
        echo $this->extensions['MailPoet\Twig\I18n']->translate("This value should be a valid url.");
        echo "',
      number: '";
        // line 245
        echo $this->extensions['MailPoet\Twig\I18n']->translate("This value should be a valid number.");
        echo "',
      integer: '";
        // line 246
        echo $this->extensions['MailPoet\Twig\I18n']->translate("This value should be a valid integer.");
        echo "',
      digits: '";
        // line 247
        echo $this->extensions['MailPoet\Twig\I18n']->translate("This value should be digits.");
        echo "',
      alphanum: '";
        // line 248
        echo $this->extensions['MailPoet\Twig\I18n']->translate("This value should be alphanumeric.");
        echo "'
    },
    notblank: '";
        // line 250
        echo $this->extensions['MailPoet\Twig\I18n']->translate("This value should not be blank.");
        echo "',
    required: '";
        // line 251
        echo $this->extensions['MailPoet\Twig\I18n']->translate("This value is required.");
        echo "',
    pattern: '";
        // line 252
        echo $this->extensions['MailPoet\Twig\I18n']->translate("This value seems to be invalid.");
        echo "',
    min: '";
        // line 253
        echo $this->extensions['MailPoet\Twig\I18n']->translate("This value should be greater than or equal to %s.");
        echo "',
    max: '";
        // line 254
        echo $this->extensions['MailPoet\Twig\I18n']->translate("This value should be lower than or equal to %s.");
        echo "',
    range: '";
        // line 255
        echo $this->extensions['MailPoet\Twig\I18n']->translate("This value should be between %s and %s.");
        echo "',
    minlength: '";
        // line 256
        echo $this->extensions['MailPoet\Twig\I18n']->translate("This value is too short. It should have %s characters or more.");
        echo "',
    maxlength: '";
        // line 257
        echo $this->extensions['MailPoet\Twig\I18n']->translate("This value is too long. It should have %s characters or fewer.");
        echo "',
    length: '";
        // line 258
        echo $this->extensions['MailPoet\Twig\I18n']->translate("This value length is invalid. It should be between %s and %s characters long.");
        echo "',
    mincheck: '";
        // line 259
        echo $this->extensions['MailPoet\Twig\I18n']->translate("You must select at least %s choices.");
        echo "',
    maxcheck: '";
        // line 260
        echo $this->extensions['MailPoet\Twig\I18n']->translate("You must select %s choices or fewer.");
        echo "',
    check: '";
        // line 261
        echo $this->extensions['MailPoet\Twig\I18n']->translate("You must select between %s and %s choices.");
        echo "',
    equalto: '";
        // line 262
        echo $this->extensions['MailPoet\Twig\I18n']->translate("This value should be the same.");
        echo "'
  });

  Parsley.setLocale('mailpoet');
</script>
";
        // line 267
        $this->displayBlock('after_javascript', $context, $blocks);
        // line 268
        echo "<div id=\"mailpoet-modal\"></div>
";
    }

    // line 23
    public function block_templates($context, array $blocks = [])
    {
        $macros = $this->macros;
    }

    // line 26
    public function block_container($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 27
        echo "<div class=\"wrap\">
  <div class=\"wp-header-end\"></div>
  <!-- notices -->
  <div id=\"mailpoet_notice_error\" class=\"mailpoet_notice\" style=\"display:none;\"></div>
  <div id=\"mailpoet_notice_success\" class=\"mailpoet_notice\" style=\"display:none;\"></div>
  <!-- React notices -->
  <div id=\"mailpoet_notices\"></div>

  <!-- Set FROM address modal React root -->
  <div id=\"mailpoet_set_from_address_modal\"></div>

  <!-- Set Authorize sender email React root -->
  <div id=\"mailpoet_authorize_sender_email_modal\"></div>

  <!-- title block -->
  ";
        // line 42
        $this->displayBlock('title', $context, $blocks);
        // line 43
        echo "  <!-- content block -->
  ";
        // line 44
        $this->displayBlock('content', $context, $blocks);
        // line 45
        echo "</div>
";
    }

    // line 42
    public function block_title($context, array $blocks = [])
    {
        $macros = $this->macros;
    }

    // line 44
    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
    }

    // line 50
    public function block_after_css($context, array $blocks = [])
    {
        $macros = $this->macros;
    }

    // line 188
    public function block_translations($context, array $blocks = [])
    {
        $macros = $this->macros;
    }

    // line 190
    public function block_after_translations($context, array $blocks = [])
    {
        $macros = $this->macros;
    }

    // line 267
    public function block_after_javascript($context, array $blocks = [])
    {
        $macros = $this->macros;
    }

    public function getTemplateName()
    {
        return "layout.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  607 => 267,  601 => 190,  595 => 188,  589 => 50,  583 => 44,  577 => 42,  572 => 45,  570 => 44,  567 => 43,  565 => 42,  548 => 27,  544 => 26,  538 => 23,  533 => 268,  531 => 267,  523 => 262,  519 => 261,  515 => 260,  511 => 259,  507 => 258,  503 => 257,  499 => 256,  495 => 255,  491 => 254,  487 => 253,  483 => 252,  479 => 251,  475 => 250,  470 => 248,  466 => 247,  462 => 246,  458 => 245,  454 => 244,  450 => 243,  445 => 241,  440 => 238,  428 => 229,  420 => 224,  411 => 218,  402 => 212,  395 => 207,  392 => 206,  389 => 205,  386 => 204,  383 => 203,  380 => 202,  377 => 201,  375 => 200,  369 => 198,  367 => 197,  362 => 195,  358 => 193,  356 => 191,  354 => 190,  351 => 189,  349 => 188,  346 => 187,  344 => 156,  343 => 155,  342 => 126,  338 => 124,  336 => 120,  331 => 117,  325 => 115,  323 => 114,  318 => 112,  314 => 111,  310 => 110,  306 => 109,  302 => 108,  298 => 107,  294 => 106,  290 => 105,  286 => 104,  282 => 103,  278 => 102,  274 => 101,  270 => 100,  266 => 99,  262 => 98,  258 => 97,  254 => 96,  250 => 95,  246 => 94,  242 => 93,  238 => 92,  234 => 91,  228 => 88,  224 => 87,  220 => 86,  215 => 84,  211 => 83,  207 => 82,  203 => 81,  199 => 80,  195 => 79,  191 => 78,  187 => 77,  183 => 76,  179 => 75,  175 => 74,  171 => 73,  167 => 72,  163 => 71,  157 => 68,  153 => 67,  149 => 66,  145 => 65,  141 => 64,  137 => 63,  133 => 62,  129 => 61,  125 => 60,  121 => 59,  117 => 58,  113 => 57,  109 => 56,  105 => 55,  101 => 54,  97 => 53,  93 => 51,  91 => 50,  86 => 48,  83 => 47,  81 => 26,  77 => 24,  75 => 23,  62 => 12,  53 => 6,  47 => 2,  45 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "layout.html", "/home/circleci/mailpoet/mailpoet/views/layout.html");
    }
}
