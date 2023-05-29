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

/* segments.html */
class __TwigTemplate_49af5fc06210741b26b3abed3da668ba61c26b6a5b1c29ef3bf7edd6bba11016 extends Template
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
        $this->parent = $this->loadTemplate("layout.html", "segments.html", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 4
        echo "  <div id=\"segments_container\"></div>

  <script type=\"text/javascript\">
    var mailpoet_listing_per_page = ";
        // line 7
        echo \MailPoetVendor\twig_escape_filter($this->env, ($context["items_per_page"] ?? null), "html", null, true);
        echo ";
    var mailpoet_beacon_articles = [
      '5d667fc22c7d3a7a4d77c426',
      '57f47ca3c697914f21035256',
      '5d4beee42c7d3a330e3c4207',
      '5e187a3a2c7d3a7e9ae607ff',
      '57ce079f903360649f6e56fc',
      '5d722c7104286364bc8ecf19',
      '5a574bd92c7d3a194368233e',
      '59a89621042863033a1c82e6'
    ];
    var mailpoet_custom_fields = ";
        // line 18
        echo json_encode(($context["custom_fields"] ?? null));
        echo ";
    var mailpoet_static_segments_list = ";
        // line 19
        echo json_encode(($context["static_segments_list"] ?? null));
        echo ";
    var wordpress_editable_roles_list = ";
        // line 20
        echo json_encode(($context["wordpress_editable_roles_list"] ?? null));
        echo ";
    var mailpoet_newsletters_list = ";
        // line 21
        echo json_encode(($context["newsletters_list"] ?? null));
        echo ";
    var mailpoet_product_categories = ";
        // line 22
        echo json_encode(($context["product_categories"] ?? null));
        echo ";
    var mailpoet_products = ";
        // line 23
        echo json_encode(($context["products"] ?? null));
        echo ";
    var mailpoet_membership_plans = ";
        // line 24
        echo json_encode(($context["membership_plans"] ?? null));
        echo ";
    var mailpoet_subscription_products = ";
        // line 25
        echo json_encode(($context["subscription_products"] ?? null));
        echo ";
    var mailpoet_can_use_woocommerce_memberships = ";
        // line 26
        echo json_encode(($context["can_use_woocommerce_memberships"] ?? null));
        echo ";
    var mailpoet_can_use_woocommerce_subscriptions = ";
        // line 27
        echo json_encode(($context["can_use_woocommerce_subscriptions"] ?? null));
        echo ";
    var mailpoet_woocommerce_currency_symbol = ";
        // line 28
        echo json_encode(($context["woocommerce_currency_symbol"] ?? null));
        echo ";
    var mailpoet_woocommerce_countries = ";
        // line 29
        echo json_encode(($context["woocommerce_countries"] ?? null));
        echo ";
  </script>
";
    }

    // line 33
    public function block_translations($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 34
        echo "  ";
        echo $this->extensions['MailPoet\Twig\I18n']->localize(["pageTitle" => $this->extensions['MailPoet\Twig\I18n']->translate("Lists"), "searchLabel" => $this->extensions['MailPoet\Twig\I18n']->translate("Search"), "loadingItems" => $this->extensions['MailPoet\Twig\I18n']->translate("Loading lists..."), "noItemsFound" => $this->extensions['MailPoet\Twig\I18n']->translate("No lists found"), "selectAllLabel" => $this->extensions['MailPoet\Twig\I18n']->translate("All lists on this page are selected."), "selectedAllLabel" => $this->extensions['MailPoet\Twig\I18n']->translate("All %d lists are selected."), "selectAllLink" => $this->extensions['MailPoet\Twig\I18n']->translate("Select all lists on all pages"), "clearSelection" => $this->extensions['MailPoet\Twig\I18n']->translate("Clear selection"), "permanentlyDeleted" => $this->extensions['MailPoet\Twig\I18n']->translate("%d lists were permanently deleted."), "selectBulkAction" => $this->extensions['MailPoet\Twig\I18n']->translate("Select bulk action"), "bulkActions" => $this->extensions['MailPoet\Twig\I18n']->translate("Bulk Actions"), "apply" => $this->extensions['MailPoet\Twig\I18n']->translate("Apply"), "name" => $this->extensions['MailPoet\Twig\I18n']->translate("Name"), "description" => $this->extensions['MailPoet\Twig\I18n']->translate("Description"), "segmentUpdated" => $this->extensions['MailPoet\Twig\I18n']->translate("List successfully updated!"), "segmentAdded" => $this->extensions['MailPoet\Twig\I18n']->translate("List successfully added!"), "segment" => $this->extensions['MailPoet\Twig\I18n']->translate("List"), "subscribed" => $this->extensions['MailPoet\Twig\I18n']->translate("Subscribed"), "unconfirmed" => $this->extensions['MailPoet\Twig\I18n']->translate("Unconfirmed"), "unsubscribed" => $this->extensions['MailPoet\Twig\I18n']->translate("Unsubscribed"), "inactive" => $this->extensions['MailPoet\Twig\I18n']->translate("Inactive"), "bounced" => $this->extensions['MailPoet\Twig\I18n']->translate("Bounced"), "createdOn" => $this->extensions['MailPoet\Twig\I18n']->translate("Created on"), "oneSegmentTrashed" => $this->extensions['MailPoet\Twig\I18n']->translate("1 list was moved to the trash. Note that deleting a list does not delete its subscribers."), "multipleSegmentsTrashed" => $this->extensions['MailPoet\Twig\I18n']->translate("%1\$d lists were moved to the trash. Note that deleting a list does not delete its subscribers."), "oneSegmentDeleted" => $this->extensions['MailPoet\Twig\I18n']->translate("1 list was permanently deleted. Note that deleting a list does not delete its subscribers."), "multipleSegmentsDeleted" => $this->extensions['MailPoet\Twig\I18n']->translate("%1\$d lists were permanently deleted. Note that deleting a list does not delete its subscribers."), "oneSegmentRestored" => $this->extensions['MailPoet\Twig\I18n']->translate("1 list has been restored from the Trash."), "multipleSegmentsRestored" => $this->extensions['MailPoet\Twig\I18n']->translate("%1\$d lists have been restored from the Trash."), "duplicate" => $this->extensions['MailPoet\Twig\I18n']->translate("Duplicate"), "listDuplicated" => $this->extensions['MailPoet\Twig\I18n']->translate("List \"%1\$s\" has been duplicated."), "update" => $this->extensions['MailPoet\Twig\I18n']->translate("Update"), "forceSync" => $this->extensions['MailPoet\Twig\I18n']->translate("Force Sync"), "readMore" => $this->extensions['MailPoet\Twig\I18n']->translate("Read More"), "listSynchronized" => $this->extensions['MailPoet\Twig\I18n']->translate("List \"%1\$s\" has been synchronized."), "listSynchronizationWasScheduled" => $this->extensions['MailPoet\Twig\I18n']->translate("Synchronization of the \"%1\$s\" list started. It can take several minutes to finish."), "viewSubscribers" => $this->extensions['MailPoet\Twig\I18n']->translate("View Subscribers"), "new" => $this->extensions['MailPoet\Twig\I18n']->translate("New List"), "newSegment" => $this->extensions['MailPoet\Twig\I18n']->translate("New Segment"), "edit" => $this->extensions['MailPoet\Twig\I18n']->translate("Edit"), "trash" => $this->extensions['MailPoet\Twig\I18n']->translate("Trash"), "moveToTrash" => $this->extensions['MailPoet\Twig\I18n']->translate("Move to trash"), "emptyTrash" => $this->extensions['MailPoet\Twig\I18n']->translate("Empty Trash"), "selectAll" => $this->extensions['MailPoet\Twig\I18n']->translate("Select All"), "restore" => $this->extensions['MailPoet\Twig\I18n']->translate("Restore"), "deletePermanently" => $this->extensions['MailPoet\Twig\I18n']->translate("Delete permanently"), "save" => $this->extensions['MailPoet\Twig\I18n']->translate("Save"), "trashAndDisable" => $this->extensions['MailPoet\Twig\I18n']->translate("Trash and disable"), "restoreAndEnable" => $this->extensions['MailPoet\Twig\I18n']->translate("Restore and enable"), "listScore" => $this->extensions['MailPoet\Twig\I18n']->translate("List score"), "previousPage" => $this->extensions['MailPoet\Twig\I18n']->translate("Previous page"), "firstPage" => $this->extensions['MailPoet\Twig\I18n']->translate("First page"), "nextPage" => $this->extensions['MailPoet\Twig\I18n']->translate("Next page"), "lastPage" => $this->extensions['MailPoet\Twig\I18n']->translate("Last page"), "currentPage" => $this->extensions['MailPoet\Twig\I18n']->translate("Current page"), "pageOutOf" => $this->extensions['MailPoet\Twig\I18n']->translate("of"), "numberOfItemsSingular" => $this->extensions['MailPoet\Twig\I18n']->translate("1 item"), "numberOfItemsMultiple" => $this->extensions['MailPoet\Twig\I18n']->translate("%1\$d items"), "segmentDescriptionTip" => $this->extensions['MailPoet\Twig\I18n']->translate("This text box is for your own use and is never shown to your subscribers."), "backToList" => $this->extensions['MailPoet\Twig\I18n']->translate("Back"), "showInManageSubscriptionPage" => $this->extensions['MailPoet\Twig\I18n']->translate("List visibility"), "showInManageSubscriptionPageTip" => $this->extensions['MailPoet\Twig\I18n']->translate("Show this list on the \"Manage Subscription\" page"), "subscribersInPlanCount" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("%1\$d / %2\$d", "count / total subscribers"), "subscribersInPlan" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("%s subscribers in your plan", "number of subscribers in a sending plan"), "subscribersInPlanTooltip" => $this->extensions['MailPoet\Twig\I18n']->translate("This is the total of subscribed, unconfirmed and inactive subscribers we count when you are sending with MailPoet Sending Service. The count excludes unsubscribed and bounced (invalid) email addresses."), "mailpoetSubscribers" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("%s MailPoet subscribers", "number of subscribers in the plugin"), "mailpoetSubscribersTooltipFree" => $this->extensions['MailPoet\Twig\I18n']->translate("This is the total of all subscribers including %1\$d WordPress users. To exclude WordPress users, please purchase one of our premium plans."), "mailpoetSubscribersTooltipPremium" => $this->extensions['MailPoet\Twig\I18n']->translate("This is the total of all subscribers excluding all WordPress users."), "pageTitleSegments" => $this->extensions['MailPoet\Twig\I18n']->translate("Segments"), "formPageTitle" => $this->extensions['MailPoet\Twig\I18n']->translate("Segment"), "formSegmentTitle" => $this->extensions['MailPoet\Twig\I18n']->translate("Segment"), "descriptionTip" => $this->extensions['MailPoet\Twig\I18n']->translate("This text box is for your own use and is never shown to your subscribers."), "dynamicSegmentUpdated" => $this->extensions['MailPoet\Twig\I18n']->translate("Segment successfully updated!"), "dynamicSegmentAdded" => $this->extensions['MailPoet\Twig\I18n']->translate("Segment successfully added!"), "segmentType" => $this->extensions['MailPoet\Twig\I18n']->translate("Type"), "wpUserRole" => $this->extensions['MailPoet\Twig\I18n']->translate("Subscriber"), "email" => $this->extensions['MailPoet\Twig\I18n']->translate("Email"), "nameColumn" => $this->extensions['MailPoet\Twig\I18n']->translate("Name"), "subscribersCountColumn" => $this->extensions['MailPoet\Twig\I18n']->translate("Number of subscribers"), "updatedAtColumn" => $this->extensions['MailPoet\Twig\I18n']->translate("Modified on"), "missingPluginMessageColumn" => $this->extensions['MailPoet\Twig\I18n']->translate("Missing plugin message"), "loadingDynamicSegmentItems" => $this->extensions['MailPoet\Twig\I18n']->translate("Loading data…"), "noDynamicSegmentItemsFound" => $this->extensions['MailPoet\Twig\I18n']->translate("No segments found"), "numberOfItemsSingular" => $this->extensions['MailPoet\Twig\I18n']->translate("1 item"), "numberOfItemsMultiple" => $this->extensions['MailPoet\Twig\I18n']->translate("%1\$d items"), "previousPage" => $this->extensions['MailPoet\Twig\I18n']->translate("Previous page"), "firstPage" => $this->extensions['MailPoet\Twig\I18n']->translate("First page"), "nextPage" => $this->extensions['MailPoet\Twig\I18n']->translate("Next page"), "lastPage" => $this->extensions['MailPoet\Twig\I18n']->translate("Last page"), "currentPage" => $this->extensions['MailPoet\Twig\I18n']->translate("Current page"), "pageOutOf" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("of", "Page X of Y"), "notSentYet" => $this->extensions['MailPoet\Twig\I18n']->translate("Not sent yet"), "allLinksPlaceholder" => $this->extensions['MailPoet\Twig\I18n']->translate("All links"), "noLinksHint" => $this->extensions['MailPoet\Twig\I18n']->translate("Email not sent yet!"), "selectNewsletterPlaceholder" => $this->extensions['MailPoet\Twig\I18n']->translate("Search emails"), "selectActionPlaceholder" => $this->extensions['MailPoet\Twig\I18n']->translate("Select action"), "selectUserRolePlaceholder" => $this->extensions['MailPoet\Twig\I18n']->translate("Search user roles"), "selectCustomFieldPlaceholder" => $this->extensions['MailPoet\Twig\I18n']->translate("Select custom field"), "emailActionOpened" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("opened", "Dynamic segment creation: when newsletter was opened"), "emailActionMachineOpened" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("machine-opened", "Dynamic segment creation: list of all subscribers that opened the newsletter automatically in the background"), "emailActionOpensAbsoluteCount" => $this->extensions['MailPoet\Twig\I18n']->translate("# of opens"), "emailActionMachineOpensAbsoluteCount" => $this->extensions['MailPoet\Twig\I18n']->translate("# of machine-opens"), "emailActionOpens" => $this->extensions['MailPoet\Twig\I18n']->translate("opens"), "emailActionDays" => $this->extensions['MailPoet\Twig\I18n']->translate("days"), "emailActionOpensSentence" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("{condition} {opens} opens", "The result will be \"more than 20 opens\""), "emailActionOpensDaysSentence" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("in the last {days} days", "The result will be \"in the last 5 days\""), "moreThan" => $this->extensions['MailPoet\Twig\I18n']->translate("more than"), "lessThan" => $this->extensions['MailPoet\Twig\I18n']->translate("less than"), "higherThan" => $this->extensions['MailPoet\Twig\I18n']->translate("higher than"), "lowerThan" => $this->extensions['MailPoet\Twig\I18n']->translate("lower than"), "equals" => $this->extensions['MailPoet\Twig\I18n']->translate("equals"), "notEquals" => $this->extensions['MailPoet\Twig\I18n']->translate("not equals"), "contains" => $this->extensions['MailPoet\Twig\I18n']->translate("contains"), "value" => $this->extensions['MailPoet\Twig\I18n']->translate("value"), "selectValue" => $this->extensions['MailPoet\Twig\I18n']->translate("Select value"), "checked" => $this->extensions['MailPoet\Twig\I18n']->translate("checked"), "unchecked" => $this->extensions['MailPoet\Twig\I18n']->translate("unchecked"), "unknown" => $this->extensions['MailPoet\Twig\I18n']->translate("unknown"), "notUnknown" => $this->extensions['MailPoet\Twig\I18n']->translate("not unknown"), "before" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("before", "Meaning: \"Subscriber subscribed before April\""), "after" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("after", "Meaning: \"Subscriber subscribed after April"), "on" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("on", "Meaning: \"Subscriber subscribed on a given date\""), "notOn" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("not on", "Meaning: \"Subscriber subscribed on a date other than the given date\""), "inTheLast" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("in the last", "Meaning: \"Subscriber subscribed in the last 3 days\""), "notInTheLast" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("not in the last", "Meaning: \"Subscriber subscribed not in the last 3 days\""), "emailActionNotOpened" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("not opened", "Dynamic segment creation: when newsletter was not opened"), "emailActionClicked" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("clicked", "Dynamic segment creation: when a newsletter link was clicked"), "emailActionClickedAnyEmail" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("clicked any email", "Dynamic segment creation: when a newsletter link in any email was clicked"), "emailActionNotClicked" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("not clicked", "Dynamic segment creation: when a newsletter link was not clicked"), "searchLabel" => $this->extensions['MailPoet\Twig\I18n']->translate("Search"), "segmentsTip" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Tip", "A note about dynamic segments usage"), "segmentsTipText" => $this->extensions['MailPoet\Twig\I18n']->translate("segments allow you to group your subscribers by other criteria, such as events and actions."), "segmentsTipLink" => $this->extensions['MailPoet\Twig\I18n']->translate("Read more."), "subscribedDate" => $this->extensions['MailPoet\Twig\I18n']->translate("subscribed date"), "subscriberScore" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("score", "Subscriber engagement score"), "subscriberScorePlaceholder" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("score", "Placeholder for input: subscriber engagement score"), "subscriberScoreSentence" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("{condition} {score} %", "The result will be \"higher than 20 %\""), "subscribedToList" => $this->extensions['MailPoet\Twig\I18n']->translate("subscribed to list"), "segmentsSubscriber" => $this->extensions['MailPoet\Twig\I18n']->translate("WordPress user role"), "mailpoetCustomField" => $this->extensions['MailPoet\Twig\I18n']->translate("MailPoet custom field"), "segmentsActiveMembership" => $this->extensions['MailPoet\Twig\I18n']->translate("is member of"), "woocommerceMemberships" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("WooCommerce Memberships", "Dynamic segment creation: User selects this to use any WooCommerce Memberships filters"), "selectWooMembership" => $this->extensions['MailPoet\Twig\I18n']->translate("Search membership plans"), "segmentsActiveSubscription" => $this->extensions['MailPoet\Twig\I18n']->translate("has active subscription"), "woocommerceSubscriptions" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("WooCommerce Subscriptions", "Dynamic segment creation: User selects this to use any WooCommerce Subscriptions filters"), "selectWooSubscription" => $this->extensions['MailPoet\Twig\I18n']->translate("Search subscriptions"), "searchLists" => $this->extensions['MailPoet\Twig\I18n']->translate("Search lists"), "subscriberTag" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("tag", "Subscriber tag"), "searchTags" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Search tags"), "anyOf" => $this->extensions['MailPoet\Twig\I18n']->translate("any of"), "allOf" => $this->extensions['MailPoet\Twig\I18n']->translate("all of"), "noneOf" => $this->extensions['MailPoet\Twig\I18n']->translate("none of"), "oneDynamicSegmentTrashed" => $this->extensions['MailPoet\Twig\I18n']->translate("1 segment was moved to the trash."), "multipleDynamicSegmentsTrashed" => $this->extensions['MailPoet\Twig\I18n']->translate("%1\$d segments were moved to the trash."), "oneDynamicSegmentRestored" => $this->extensions['MailPoet\Twig\I18n']->translate("1 segment has been restored from the Trash."), "multipleDynamicSegmentsRestored" => $this->extensions['MailPoet\Twig\I18n']->translate("%1\$d segments have been restored from the Trash."), "multipleDynamicSegmentsDeleted" => $this->extensions['MailPoet\Twig\I18n']->translate("%1\$d segments were permanently deleted."), "oneDynamicSegmentDeleted" => $this->extensions['MailPoet\Twig\I18n']->translate("1 segment was permanently deleted."), "wooNumberOfOrders" => $this->extensions['MailPoet\Twig\I18n']->translate("# of orders"), "moreThan" => $this->extensions['MailPoet\Twig\I18n']->translate("more than"), "lessThan" => $this->extensions['MailPoet\Twig\I18n']->translate("less than"), "wooNumberOfOrdersCount" => $this->extensions['MailPoet\Twig\I18n']->translate("count"), "daysPlaceholder" => $this->extensions['MailPoet\Twig\I18n']->translate("days"), "days" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("days", "Appears together with `inTheLast` when creating a new WooCommerce segment based on the number of orders."), "inTheLast" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("in the last", "Appears together with `days` when creating a new WooCommerce segment based on the number of orders."), "wooNumberOfOrdersOrders" => $this->extensions['MailPoet\Twig\I18n']->translate("orders"), "wooPurchasedCategory" => $this->extensions['MailPoet\Twig\I18n']->translate("purchased in category"), "wooPurchasedProduct" => $this->extensions['MailPoet\Twig\I18n']->translate("purchased product"), "wooTotalSpent" => $this->extensions['MailPoet\Twig\I18n']->translate("total spent"), "wooCustomerInCountry" => $this->extensions['MailPoet\Twig\I18n']->translate("is in country"), "wooTotalSpentAmount" => $this->extensions['MailPoet\Twig\I18n']->translate("amount"), "selectWooPurchasedCategory" => $this->extensions['MailPoet\Twig\I18n']->translate("Search categories"), "selectWooPurchasedProduct" => $this->extensions['MailPoet\Twig\I18n']->translate("Search products"), "selectWooCountry" => $this->extensions['MailPoet\Twig\I18n']->translate("Search countries"), "woocommerce" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("WooCommerce", "Dynamic segment creation: User selects this to use any woocommerce filters"), "dynamicSegmentSizeIsCalculated" => $this->extensions['MailPoet\Twig\I18n']->translate("Calculating segment size…"), "dynamicSegmentSizeCalculatingTimeout" => $this->extensions['MailPoet\Twig\I18n']->translate("It's taking longer than usual to generate the segment, which may be due to a complex configuration. Try using fewer or simpler conditions."), "dynamicSegmentSize" => $this->extensions['MailPoet\Twig\I18n']->translate("This segment has %1\$d subscribers."), "learnMore" => $this->extensions['MailPoet\Twig\I18n']->translate("Learn more."), "unknownBadgeName" => $this->extensions['MailPoet\Twig\I18n']->translate("Unknown"), "unknownBadgeTooltip" => $this->extensions['MailPoet\Twig\I18n']->translate("Not enough data."), "tooltipUnknown" => $this->extensions['MailPoet\Twig\I18n']->translate("Fewer than 3 emails sent"), "excellentBadgeName" => $this->extensions['MailPoet\Twig\I18n']->translate("Excellent"), "excellentBadgeTooltip" => $this->extensions['MailPoet\Twig\I18n']->translate("Congrats!"), "tooltipExcellent" => $this->extensions['MailPoet\Twig\I18n']->translate("50% or more"), "goodBadgeName" => $this->extensions['MailPoet\Twig\I18n']->translate("Good"), "goodBadgeTooltip" => $this->extensions['MailPoet\Twig\I18n']->translate("Good stuff."), "tooltipGood" => $this->extensions['MailPoet\Twig\I18n']->translate("between 20 and 50%"), "averageBadgeName" => $this->extensions['MailPoet\Twig\I18n']->translate("Low"), "averageBadgeTooltip" => $this->extensions['MailPoet\Twig\I18n']->translate("Something to improve."), "tooltipAverage" => $this->extensions['MailPoet\Twig\I18n']->translate("20% or fewer"), "engagementScoreDescription" => $this->extensions['MailPoet\Twig\I18n']->translate("Average percent of emails subscribers read in the last year"), "addCondition" => $this->extensions['MailPoet\Twig\I18n']->translate("Add a condition"), "privacyProtectionNotice" => $this->extensions['MailPoet\Twig\I18n']->translate("Due to email privacy protections, some opens may not be tracked. Consider using a different engagement metric."), "premiumFeatureMultipleConditions" => $this->extensions['MailPoet\Twig\I18n']->translate("Your current MailPoet plan does not support advanced segments with multiple conditions. Upgrade to the MailPoet Business plan to more precisely target your emails based on how people engage with your business. [link]Learn more.[/link]"), "premiumBannerCtaFree" => $this->extensions['MailPoet\Twig\I18n']->translate("Upgrade"), "premiumFeatureDescription" => $this->extensions['MailPoet\Twig\I18n']->translate("Your current MailPoet plan includes advanced features, but they require the MailPoet Premium plugin to be installed and activated."), "premiumFeatureButtonDownloadPremium" => $this->extensions['MailPoet\Twig\I18n']->translate("Download MailPoet Premium plugin"), "premiumFeatureButtonActivatePremium" => $this->extensions['MailPoet\Twig\I18n']->translate("Activate MailPoet Premium plugin"), "premiumFeatureDescriptionSubscribersLimitReached" => $this->extensions['MailPoet\Twig\I18n']->translate("Congratulations, you now have [subscribersCount] subscribers! Your plan is limited to [subscribersLimit] subscribers. You need to upgrade now to be able to continue using MailPoet."), "premiumFeatureButtonUpgradePlan" => $this->extensions['MailPoet\Twig\I18n']->translate("Upgrade your plan")]);
        // line 251
        echo "
";
    }

    // line 254
    public function block_after_translations($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 255
        echo do_action("mailpoet_segments_translations_after");
        echo "
";
    }

    public function getTemplateName()
    {
        return "segments.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  138 => 255,  134 => 254,  129 => 251,  126 => 34,  122 => 33,  115 => 29,  111 => 28,  107 => 27,  103 => 26,  99 => 25,  95 => 24,  91 => 23,  87 => 22,  83 => 21,  79 => 20,  75 => 19,  71 => 18,  57 => 7,  52 => 4,  48 => 3,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "segments.html", "/home/circleci/mailpoet/mailpoet/views/segments.html");
    }
}
