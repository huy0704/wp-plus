<?php

namespace Nextend\SmartSlider3\Application\Admin;

use JUri;
use Nextend\Framework\Pattern\MVCHelperTrait;

trait TraitAdminUrl {

    /** @var MVCHelperTrait */
    protected $MVCHelper;

    public function getUrlGettingStarted() {

        return $this->createUrl(array(
            "sliders/gettingstarted"
        ));
    }

    public function getUrlGettingStartedDontShow() {

        return $this->createUrl(array(
            "sliders/gettingStartedDontShow"
        ));
    }

    public function getUrlDashboard() {

        return $this->createUrl(array(
            "sliders/index"
        ));
    }

    public function getUrlPaginator() {

        return $this->createAjaxUrl(array(
            'sliders/pagination',
        ));
    }

    public function getUrlDashboardOrderBy($orderBy, $direction, $page = null, $limit = null) {
        $args              = array();
        $args[$orderBy]    = $direction;
        $args['pageIndex'] = $page;
        $args['limit']     = $limit;

        return $this->createAjaxUrl(array(
            'sliders/orderby',
            $args
        ), true);
    }

    public function getUrlDashboardExportAll($groupID) {

        return $this->createUrl(array(
            "sliders/exportAll",
            array(
                'currentGroupID' => $groupID,
                'sliders'        => array()
            )
        ), true);
    }

    public function getAjaxUrlHideReview() {

        return $this->createAjaxUrl(array(
            'sliders/HideReview'
        ));
    }

    public function getUrlHidePromoUpgrade() {

        return $this->createUrl(array(
            'sliders/hidePromoUpgrade'
        ), true);
    }

    /**
     * @return string
     */
    public function getUrlTrash() {

        return $this->createUrl(array(
            "sliders/trash"
        ));
    }

    /**
     * @return string
     */
    public function getUrlImport($groupID = 0) {

        return $this->createUrl(array(
            "sliders/import",
            array(
                'groupID' => $groupID
            )
        ));
    }

    /**
     * @return string
     */
    public function getAjaxUrlImport($groupID = 0) {

        return $this->createAjaxUrl(array(
            "sliders/import",
            array(
                'groupID' => $groupID
            )
        ));
    }

    /**
     * @param int $sliderID
     * @param int $groupID
     *
     * @return string
     */
    public function getUrlSliderEdit($sliderID, $groupID = 0) {

        return $this->createUrl(array(
            "slider/edit",
            array(
                'sliderid' => $sliderID,
                'groupID'  => $groupID
            )
        ));
    }

    public function getAjaxUrlSliderEdit($sliderID) {

        return $this->createAjaxUrl(array(
            "slider/edit",
            array(
                'sliderid' => $sliderID
            )
        ));
    }

    /**
     * @param int $sliderID
     * @param int $groupID
     *
     * @return string
     */
    public function getUrlSliderSimpleEdit($sliderID, $groupID = 0) {

        return $this->createUrl(array(
            "slider/simpleedit",
            array(
                'sliderid' => $sliderID,
                'groupID'  => $groupID
            )
        ));
    }

    /**
     * @param int $sliderID
     * @param int $groupID
     *
     * @return string
     */
    public function getUrlSliderSimpleEditAddSlide($sliderID, $groupID = 0) {

        return $this->createUrl(array(
            "slider/simpleeditaddslide",
            array(
                'sliderid' => $sliderID,
                'groupID'  => $groupID
            )
        ));
    }

    /**
     * @param int $sliderID
     * @param int $groupID
     *
     * @return string
     */
    public function getUrlSliderMoveToTrash($sliderID, $groupID) {
        return $this->createUrl(array(
            'slider/trash',
            array(
                'sliderid' => $sliderID,
                'groupID'  => $groupID
            )
        ), true);
    }

    /**
     * @param int $sliderID
     * @param int $groupID
     *
     * @return string
     */
    public function getUrlSliderDuplicate($sliderID, $groupID) {
        return $this->createUrl(array(
            'slider/duplicate',
            array(
                'sliderid' => $sliderID,
                'groupID'  => $groupID
            )
        ), true);
    }

    /**
     * @param int $sliderID
     *
     * @return string
     */
    public function getUrlSliderExport($sliderID) {
        return $this->createUrl(array(
            'slider/export',
            array(
                'sliderid' => $sliderID
            )
        ), true);
    }

    /**
     * @param int $sliderID
     *
     * @return string
     */
    public function getUrlSliderExportHtml($sliderID) {
        return $this->createUrl(array(
            'slider/exporthtml',
            array(
                'sliderid' => $sliderID
            )
        ), true);
    }

    /**
     * @param int $sliderID
     *
     * @return string
     */
    public function getUrlSliderClearCache($sliderID) {
        return $this->createUrl(array(
            'slider/clearcache',
            array(
                'sliderid' => $sliderID
            )
        ), true);
    }

    /**
     * @param int $sliderID
     *
     * @return string
     */
    public function getUrlPreviewIndex($sliderID) {

        return $this->createUrl(array(
            "preview/index",
            array(
                'sliderid' => $sliderID
            )
        ), true);
    }

    public function getUrlPreviewFull($sliderID) {

        return $this->createUrl(array(
            "preview/full",
            array(
                'sliderid' => $sliderID
            )
        ), true);
    }

    /**
     * @param int         $sliderID
     * @param bool|string $slideID
     *
     * @return string
     */
    public function getUrlPreviewSlider($sliderID, $slideID = false) {
        $args = array(
            'sliderid' => $sliderID
        );
        if ($slideID) {
            $args['slideId'] = $slideID;
        }

        return $this->createUrl(array(
            "preview/slider",
            $args
        ), true);
    }

    /**
     * @param int $generatorID
     *
     * @return string
     */
    public function getUrlPreviewGenerator($generatorID) {

        return $this->createUrl(array(
            "preview/generator",
            array(
                'generator_id' => $generatorID
            )
        ), true);
    }

    public function getUrlSlidesUniversal($sliderID, $groupID) {

        return $this->createUrl(array(
            "slides/index",
            array(
                'groupID'  => $groupID,
                'sliderid' => $sliderID
            )
        ));
    }

    public function getAjaxUrlSlidesUniversal($sliderID, $groupID) {

        return $this->createAjaxUrl(array(
            "slides/index",
            array(
                'groupID'  => $groupID,
                'sliderid' => $sliderID
            )
        ));
    }

    public function getAjaxUrlSlidesCreate() {

        return $this->createAjaxUrl(array(
            "slider/create"
        ));
    }

    public function getUrlSlideEdit($slideID, $sliderID, $groupID) {

        return $this->createUrl(array(
            "slides/edit",
            array(
                'groupID'  => $groupID,
                'sliderid' => $sliderID,
                'slideid'  => $slideID
            )
        ));
    }

    public function getUrlSlidePublish($slideID, $sliderID, $groupID) {

        return $this->createUrl(array(
            "slides/publish",
            array(
                'groupID'  => $groupID,
                'sliderid' => $sliderID,
                'slideid'  => $slideID
            )
        ), true);
    }

    public function getUrlSlideUnPublish($slideID, $sliderID, $groupID) {

        return $this->createUrl(array(
            "slides/unpublish",
            array(
                'groupID'  => $groupID,
                'sliderid' => $sliderID,
                'slideid'  => $slideID
            )
        ), true);
    }

    public function getUrlGeneratorCreate($sliderID, $groupID) {

        return $this->createUrl(array(
            "generator/create",
            array(
                'sliderid' => $sliderID,
                'groupID'  => $groupID
            )
        ));
    }

    /**
     * @param string $generatorGroupName
     * @param int    $sliderID
     * @param int    $groupID
     *
     * @return string
     */
    public function getUrlGeneratorCheckConfiguration($generatorGroupName, $sliderID, $groupID) {

        return $this->createUrl(array(
            "generator/checkConfiguration",
            array(
                'sliderid' => $sliderID,
                'groupID'  => $groupID,
                'group'    => $generatorGroupName
            )
        ));
    }

    /**
     * @param string $generatorGroupName
     * @param int    $sliderID
     * @param int    $groupID
     *
     * @return string
     */
    public function getAjaxUrlGeneratorCheckConfiguration($generatorGroupName, $sliderID, $groupID) {

        return $this->createAjaxUrl(array(
            "generator/checkConfiguration",
            array(
                'sliderid' => $sliderID,
                'groupID'  => $groupID,
                'group'    => $generatorGroupName
            )
        ));
    }

    /**
     * @param string $generatorGroupName
     * @param int    $sliderID
     * @param int    $groupID
     *
     * @return string
     */
    public function getUrlGeneratorCreateStep2($generatorGroupName, $sliderID, $groupID) {

        return $this->createUrl(array(
            "generator/createStep2",
            array(
                'sliderid' => $sliderID,
                'groupID'  => $groupID,
                'group'    => $generatorGroupName
            )
        ));
    }

    /**
     * @param string $generatorGroupName
     * @param string $generatorTypeName
     * @param int    $sliderID
     * @param int    $groupID
     *
     * @return string
     */
    public function getUrlGeneratorCreateSettings($generatorGroupName, $generatorTypeName, $sliderID, $groupID) {

        return $this->createUrl(array(
            "generator/createSettings",
            array(
                'sliderid' => $sliderID,
                'groupID'  => $groupID,
                'group'    => $generatorGroupName,
                'type'     => $generatorTypeName
            )
        ));
    }

    /**
     * @param string $generatorGroupName
     * @param string $generatorTypeName
     * @param int    $sliderID
     * @param int    $groupID
     *
     * @return string
     */
    public function getAjaxUrlGeneratorCreateSettings($generatorGroupName, $generatorTypeName, $sliderID, $groupID) {

        return $this->createAjaxUrl(array(
            "generator/createSettings",
            array(
                'sliderid' => $sliderID,
                'groupID'  => $groupID,
                'group'    => $generatorGroupName,
                'type'     => $generatorTypeName
            )
        ));
    }

    public function getUrlGeneratorEdit($generatorID, $groupID) {

        return $this->createUrl(array(
            "generator/edit",
            array(
                'generator_id' => $generatorID,
                'groupID'      => $groupID
            )
        ));
    }

    public function getAjaxUrlGeneratorEdit($generatorID, $groupID) {

        return $this->createAjaxUrl(array(
            "generator/edit",
            array(
                'generator_id' => $generatorID,
                'groupID'      => $groupID
            )
        ));
    }

    /**
     *
     * @return string
     */
    public function getUrlSettingsDefault() {
        return $this->createUrl(array(
            'settings/default'
        ));
    }

    /**
     *
     * @return string
     */
    public function getAjaxUrlSettingsDefault() {
        return $this->createAjaxUrl(array(
            'settings/default'
        ));
    }

    /**
     *
     * @return string
     */
    public function getUrlSettingsClearCache() {
        return $this->createUrl(array(
            'settings/clearcache',
        ));
    }

    /**
     *
     * @return string
     */
    public function getAjaxUrlSettingsClearCache() {
        return $this->createAjaxUrl(array(
            'settings/clearcache',
        ));
    }

    /**
     *
     * @return string
     */
    public function getUrlSettingsFramework() {
        return $this->createUrl(array(
            'settings/framework'
        ));
    }

    /**
     *
     * @return string
     */
    public function getAjaxUrlSettingsFramework() {
        return $this->createAjaxUrl(array(
            'settings/framework'
        ));
    }

    /**
     *
     * @return string
     */
    public function getUrlSettingsFonts() {
        return $this->createUrl(array(
            'settings/fonts'
        ));
    }

    /**
     *
     * @return string
     */
    public function getAjaxUrlSettingsFonts() {
        return $this->createAjaxUrl(array(
            'settings/fonts'
        ));
    }

    /**
     *
     * @return string
     */
    public function getUrlSettingsItemDefaults() {
        return $this->createUrl(array(
            'settings/itemDefaults'
        ));
    }

    /**
     *
     * @return string
     */
    public function getAjaxUrlSettingsItemDefaults() {
        return $this->createAjaxUrl(array(
            'settings/itemDefaults'
        ));
    }

    /**
     * @param string $generatorName
     *
     * @return string
     */
    public function getUrlSettingsGenerator($generatorName) {
        return $this->createUrl(array(
            'settings/generatorconfigure',
            array(
                'group' => $generatorName
            )
        ));
    }

    public function getAjaxUrlSettingsGenerator($generatorName) {
        return $this->createAjaxUrl(array(
            'settings/generatorconfigure',
            array(
                'group' => $generatorName
            )
        ));
    }

    /**
     *
     * @return string
     */
    public function getUrlHelp() {
        return $this->createUrl(array(
            'help/index'
        ));
    }

    /**
     *
     * @return string
     */
    public function getUrlHelpBrowserIncompatible() {
        return $this->createUrl(array(
            'help/browserincompatible'
        ));
    }

    /**
     *
     * @return string
     */
    public function getUrlHelpTestApi() {
        return $this->createUrl(array(
            'help/testApi'
        ));
    }

    /**
     *
     * @return string
     */
    public function getUrlHelpRepairDatabase() {
    }

    public function getUrlUpdateDownload() {
        return $this->createUrl(array(
            'update/update'
        ), true);
    }

    public function getUrlDeauthorizeLicense() {
        return $this->createUrl(array('license/deauthorize'), true);
    }

    public function getAjaxUrlLicenseAdd() {

        return $this->createAjaxUrl(array(
            'license/add'
        ));
    }

    public function getAjaxUrlImage() {

        return $this->createAjaxUrl(array(
            'image/index'
        ));
    }

    public function getAjaxUrlBrowse() {

        return $this->createAjaxUrl(array(
            'browse/index'
        ));
    }

    public function getAjaxUrlContentSearchContent() {

        return $this->createAjaxUrl(array(
            'content/searchcontent'
        ));
    }

    public function getAjaxUrlSubscribed() {

        return $this->createAjaxUrl(array(
            'settings/subscribed'
        ));
    }
}