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

/* forms.html */
class __TwigTemplate_8a6f0398fffc82506ba46e70538b728a453c02bea83ac685560c22b44c40173a extends Template
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
        $this->parent = $this->loadTemplate("layout.html", "forms.html", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 4
        echo "  <div id=\"forms_container\"></div>

  <div>
    <p class=\"mailpoet_sending_methods_help help\">
      ";
        // line 8
        echo MailPoet\Util\Helpers::replaceLinkTags($this->extensions['MailPoet\Twig\I18n']->translate("<strong>Tip:</strong> check out [link]this list[/link] of form plugins that integrate with MailPoet."), "https://kb.mailpoet.com/article/198-list-of-forms-plugins-that-work-with-mailpoet?utm_source=plugin&utm_medium=settings&utm_campaign=helpdocs", ["target" => "_blank", "id" => "mailpoet_helper_link", "data-beacon-article" => "5953a9720428637ff8d42272"]);
        // line 11
        echo "
    </p>
  </div>

  <script type=\"text/javascript\">
    var mailpoet_listing_per_page = ";
        // line 16
        echo \MailPoetVendor\twig_escape_filter($this->env, ($context["items_per_page"] ?? null), "html", null, true);
        echo ";
    var mailpoet_segments = ";
        // line 17
        echo json_encode(($context["segments"] ?? null));
        echo ";
    var mailpoet_form_template_selection_url =
      \"";
        // line 19
        echo admin_url("admin.php?page=mailpoet-form-editor-template-selection");
        echo "\";
    var mailpoet_form_edit_url =
      \"";
        // line 21
        echo admin_url("admin.php?page=mailpoet-form-editor&id=");
        echo "\";
    var mailpoet_beacon_articles = [
      '5fac13f2cff47e00160b8dff',
      '5e43d3ec2c7d3a7e9ae79da9',
      '5e3a166204286364bc94dda4',
      '5e95cc092c7d3a7e9aeae815',
      '58a718a6dd8c8e56bfa7cad6',
      '5d1f468504286369ad8d57ff'
    ];

    var mailpoet_display_nps_poll = ";
        // line 31
        echo json_encode(($context["display_nps_survey"] ?? null));
        echo ";

    ";
        // line 33
        if (($context["display_nps_survey"] ?? null)) {
            // line 34
            echo "      var mailpoet_display_nps_form = true;
      var mailpoet_current_wp_user = ";
            // line 35
            echo json_encode(($context["current_wp_user"] ?? null));
            echo ";
      var mailpoet_current_wp_user_firstname = '";
            // line 36
            echo \MailPoetVendor\twig_escape_filter($this->env, ($context["current_wp_user_firstname"] ?? null), "html", null, true);
            echo "';
      var mailpoet_review_request_illustration_url = '";
            // line 37
            echo $this->extensions['MailPoet\Twig\Assets']->generateCdnUrl("review-request/review-request-illustration.20190815-1427.svg");
            echo "';
    ";
        }
        // line 39
        echo "  </script>
";
    }

    // line 42
    public function block_translations($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 43
        echo "  ";
        echo $this->extensions['MailPoet\Twig\I18n']->localize(["pageTitle" => $this->extensions['MailPoet\Twig\I18n']->translate("Forms"), "searchLabel" => $this->extensions['MailPoet\Twig\I18n']->translate("Search"), "loadingItems" => $this->extensions['MailPoet\Twig\I18n']->translate("Loading forms..."), "noItemsFound" => $this->extensions['MailPoet\Twig\I18n']->translate("No forms were found. Why not create a new one?"), "selectAllLabel" => $this->extensions['MailPoet\Twig\I18n']->translate("All forms on this page are selected."), "selectedAllLabel" => $this->extensions['MailPoet\Twig\I18n']->translate("All %d forms are selected."), "selectAllLink" => $this->extensions['MailPoet\Twig\I18n']->translate("Select all forms on all pages"), "clearSelection" => $this->extensions['MailPoet\Twig\I18n']->translate("Clear selection"), "permanentlyDeleted" => $this->extensions['MailPoet\Twig\I18n']->translate("%d forms permanently deleted."), "selectBulkAction" => $this->extensions['MailPoet\Twig\I18n']->translate("Select bulk action"), "bulkActions" => $this->extensions['MailPoet\Twig\I18n']->translate("Bulk Actions"), "apply" => $this->extensions['MailPoet\Twig\I18n']->translate("Apply"), "filter" => $this->extensions['MailPoet\Twig\I18n']->translate("Filter"), "emptyTrash" => $this->extensions['MailPoet\Twig\I18n']->translate("Empty Trash"), "selectAll" => $this->extensions['MailPoet\Twig\I18n']->translate("Select All"), "restore" => $this->extensions['MailPoet\Twig\I18n']->translate("Restore"), "deletePermanently" => $this->extensions['MailPoet\Twig\I18n']->translate("Delete Permanently"), "status" => $this->extensions['MailPoet\Twig\I18n']->translate("Status"), "active" => $this->extensions['MailPoet\Twig\I18n']->translate("Active"), "inactive" => $this->extensions['MailPoet\Twig\I18n']->translate("Not Active"), "formActivated" => $this->extensions['MailPoet\Twig\I18n']->translate("Your Form is now activated!"), "previousPage" => $this->extensions['MailPoet\Twig\I18n']->translate("Previous page"), "firstPage" => $this->extensions['MailPoet\Twig\I18n']->translate("First page"), "nextPage" => $this->extensions['MailPoet\Twig\I18n']->translate("Next page"), "lastPage" => $this->extensions['MailPoet\Twig\I18n']->translate("Last page"), "currentPage" => $this->extensions['MailPoet\Twig\I18n']->translate("Current Page"), "pageOutOf" => $this->extensions['MailPoet\Twig\I18n']->translate("of"), "numberOfItemsSingular" => $this->extensions['MailPoet\Twig\I18n']->translate("1 item"), "numberOfItemsMultiple" => $this->extensions['MailPoet\Twig\I18n']->translate("%1\$d items"), "formName" => $this->extensions['MailPoet\Twig\I18n']->translate("Name"), "noName" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("no name", "fallback for forms without a name in a form list"), "segments" => $this->extensions['MailPoet\Twig\I18n']->translate("Lists"), "type" => $this->extensions['MailPoet\Twig\I18n']->translate("Type"), "userChoice" => $this->extensions['MailPoet\Twig\I18n']->translate("User choice:"), "signups" => $this->extensions['MailPoet\Twig\I18n']->translate("Sign-ups"), "updatedAt" => $this->extensions['MailPoet\Twig\I18n']->translate("Modified date"), "oneFormTrashed" => $this->extensions['MailPoet\Twig\I18n']->translate("1 form was moved to the trash."), "multipleFormsTrashed" => $this->extensions['MailPoet\Twig\I18n']->translate("%1\$d forms were moved to the trash."), "oneFormDeleted" => $this->extensions['MailPoet\Twig\I18n']->translate("1 form was permanently deleted."), "multipleFormsDeleted" => $this->extensions['MailPoet\Twig\I18n']->translate("%1\$d forms were permanently deleted."), "oneFormRestored" => $this->extensions['MailPoet\Twig\I18n']->translate("1 form has been restored from the trash."), "multipleFormsRestored" => $this->extensions['MailPoet\Twig\I18n']->translate("%1\$d forms have been restored from the trash."), "edit" => $this->extensions['MailPoet\Twig\I18n']->translate("Edit"), "duplicate" => $this->extensions['MailPoet\Twig\I18n']->translate("Duplicate"), "formDuplicated" => $this->extensions['MailPoet\Twig\I18n']->translate("Form \"%1\$s\" has been duplicated."), "trash" => $this->extensions['MailPoet\Twig\I18n']->translate("Trash"), "moveToTrash" => $this->extensions['MailPoet\Twig\I18n']->translate("Move to trash"), "new" => $this->extensions['MailPoet\Twig\I18n']->translate("New Form"), "placeFormBellowPages" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Below pages", "This is a text on a widget that leads to settings for form placement"), "placeFixedBarFormOnPages" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Fixed bar", "This is a text on a widget that leads to settings for form placement - form type is fixed bar"), "placeSlideInFormOnPages" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Slide–in", "This is a text on a widget that leads to settings for form placement - form type is slide in"), "placePopupFormOnPages" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Pop-up", "This is a text on a widget that leads to settings for form placement - form type is pop-up, it will be displayed on page in a small modal window"), "placeFormOthers" => $this->extensions['MailPoet\Twig\I18n']->translateWithContext("Others (widget)", "Placement of the form using theme widget"), "formSettingsCorrupted" => $this->extensions['MailPoet\Twig\I18n']->translate("Form settings of \"%1\$s\" form are corrupted. Please [link]reconfigure the form in the editor[/link].")]);
        // line 101
        echo "
";
    }

    // line 104
    public function block_after_javascript($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 105
        echo "<script type=\"text/javascript\">
  jQuery('#mailpoet_helper_link').on('click', function() {
    MailPoet.trackEvent('Forms page > link to doc page');
  });
</script>
";
    }

    public function getTemplateName()
    {
        return "forms.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  138 => 105,  134 => 104,  129 => 101,  126 => 43,  122 => 42,  117 => 39,  112 => 37,  108 => 36,  104 => 35,  101 => 34,  99 => 33,  94 => 31,  81 => 21,  76 => 19,  71 => 17,  67 => 16,  60 => 11,  58 => 8,  52 => 4,  48 => 3,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "forms.html", "/home/circleci/mailpoet/mailpoet/views/forms.html");
    }
}
