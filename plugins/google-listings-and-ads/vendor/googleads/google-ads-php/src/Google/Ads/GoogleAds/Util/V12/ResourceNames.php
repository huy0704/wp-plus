<?php

/**
 * Copyright 2022 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

// Generated code ; DO NOT EDIT.

namespace Google\Ads\GoogleAds\Util\V12;
use Google\Ads\GoogleAds\V12\Services\AccountLinkServiceClient;
use Google\Ads\GoogleAds\V12\Services\AdGroupAdServiceClient;
use Google\Ads\GoogleAds\V12\Services\AdGroupCriterionServiceClient;
use Google\Ads\GoogleAds\V12\Services\AdGroupServiceClient;
use Google\Ads\GoogleAds\V12\Services\AdServiceClient;
use Google\Ads\GoogleAds\V12\Services\AssetGroupAssetServiceClient;
use Google\Ads\GoogleAds\V12\Services\AssetGroupListingGroupFilterServiceClient;
use Google\Ads\GoogleAds\V12\Services\AssetGroupServiceClient;
use Google\Ads\GoogleAds\V12\Services\BillingSetupServiceClient;
use Google\Ads\GoogleAds\V12\Services\CampaignBudgetServiceClient;
use Google\Ads\GoogleAds\V12\Services\CampaignCriterionServiceClient;
use Google\Ads\GoogleAds\V12\Services\CampaignServiceClient;
use Google\Ads\GoogleAds\V12\Services\ConversionActionServiceClient;
use Google\Ads\GoogleAds\V12\Services\CustomerServiceClient;
use Google\Ads\GoogleAds\V12\Services\CustomerUserAccessServiceClient;
use Google\Ads\GoogleAds\V12\Services\MerchantCenterLinkServiceClient;
use Google\ApiCore\PathTemplate;

/**
 * Provides resource names for Google Ads API entities.
 */
final class ResourceNames
{

    /**
     * Generates a resource name of account budget type.
     *
     * @param string $customerId
     * @param string $accountBudgetId
     * @return string the account budget resource name
     */
    public static function forAccountBudget(
        $customerId,
        $accountBudgetId
    ): string {
        $pathTemplate = new PathTemplate(
            'customers/{customer_id}/accountBudgets/{account_budget_id}'
        );
        return $pathTemplate->render([
            'customer_id' => $customerId,
            'account_budget_id' => $accountBudgetId,
        ]);
    }

    /**
     * Generates a resource name of account link type.
     *
     * @param string $customerId
     * @param string $accountLinkId
     * @return string the account link resource name
     */
    public static function forAccountLink(
        $customerId,
        $accountLinkId
    ): string {
        return AccountLinkServiceClient::accountLinkName(
            $customerId,
            $accountLinkId
        );
    }

    /**
     * Generates a resource name of ad type.
     *
     * @param string $customerId
     * @param string $adId
     * @return string the ad resource name
     */
    public static function forAd(
        $customerId,
        $adId
    ): string {
        return AdServiceClient::adName(
            $customerId,
            $adId
        );
    }

    /**
     * Generates a resource name of ad group type.
     *
     * @param string $customerId
     * @param string $adGroupId
     * @return string the ad group resource name
     */
    public static function forAdGroup(
        $customerId,
        $adGroupId
    ): string {
        return AdGroupServiceClient::adGroupName(
            $customerId,
            $adGroupId
        );
    }

    /**
     * Generates a resource name of ad group ad type.
     *
     * @param string $customerId
     * @param string $adGroupId
     * @param string $adId
     * @return string the ad group ad resource name
     */
    public static function forAdGroupAd(
        $customerId,
        $adGroupId,
        $adId
    ): string {
        return AdGroupAdServiceClient::adGroupAdName(
            $customerId,
            $adGroupId,
            $adId
        );
    }

    /**
     * Generates a resource name of ad group ad label type.
     *
     * @param string $customerId
     * @param string $adGroupId
     * @param string $adId
     * @param string $labelId
     * @return string the ad group ad label resource name
     */
    public static function forAdGroupAdLabel(
        $customerId,
        $adGroupId,
        $adId,
        $labelId
    ): string {
        return AdGroupAdLabelServiceClient::adGroupAdLabelName(
            $customerId,
            $adGroupId,
            $adId,
            $labelId
        );
    }

    /**
     * Generates a resource name of ad group criterion type.
     *
     * @param string $customerId
     * @param string $adGroupId
     * @param string $criterionId
     * @return string the ad group criterion resource name
     */
    public static function forAdGroupCriterion(
        $customerId,
        $adGroupId,
        $criterionId
    ): string {
        return AdGroupCriterionServiceClient::adGroupCriterionName(
            $customerId,
            $adGroupId,
            $criterionId
        );
    }

    /**
     * Generates a resource name of asset type.
     *
     * @param string $customerId
     * @param string $assetId
     * @return string the asset resource name
     */
    public static function forAsset(
        $customerId,
        $assetId
    ): string {
        $pathTemplate = new PathTemplate(
            'customers/{customer_id}/assets/{asset_id}'
        );
        return $pathTemplate->render([
            'customer_id' => $customerId,
            'asset_id' => $assetId,
        ]);
    }

    /**
     * Generates a resource name of asset group type.
     *
     * @param string $customerId
     * @param string $assetGroupId
     * @return string the asset group resource name
     */
    public static function forAssetGroup(
        $customerId,
        $assetGroupId
    ): string {
        return AssetGroupServiceClient::assetGroupName(
            $customerId,
            $assetGroupId
        );
    }

    /**
     * Generates a resource name of asset group asset type.
     *
     * @param string $customerId
     * @param string $assetGroupId
     * @param string $assetId
     * @param string $fieldType
     * @return string the asset group asset resource name
     */
    public static function forAssetGroupAsset(
        $customerId,
        $assetGroupId,
        $assetId,
        $fieldType
    ): string {
        return AssetGroupAssetServiceClient::assetGroupAssetName(
            $customerId,
            $assetGroupId,
            $assetId,
            $fieldType
        );
    }

    /**
     * Generates a resource name of asset group listing group filter type.
     *
     * @param string $customerId
     * @param string $assetGroupId
     * @param string $listingGroupFilterId
     * @return string the asset group listing group filter resource name
     */
    public static function forAssetGroupListingGroupFilter(
        $customerId,
        $assetGroupId,
        $listingGroupFilterId
    ): string {
        return AssetGroupListingGroupFilterServiceClient::assetGroupListingGroupFilterName(
            $customerId,
            $assetGroupId,
            $listingGroupFilterId
        );
    }

    /**
     * Generates a resource name of billing setup type.
     *
     * @param string $customerId
     * @param string $billingSetupId
     * @return string the billing setup resource name
     */
    public static function forBillingSetup(
        $customerId,
        $billingSetupId
    ): string {
        return BillingSetupServiceClient::billingSetupName(
            $customerId,
            $billingSetupId
        );
    }

    /**
     * Generates a resource name of campaign type.
     *
     * @param string $customerId
     * @param string $campaignId
     * @return string the campaign resource name
     */
    public static function forCampaign(
        $customerId,
        $campaignId
    ): string {
        return CampaignServiceClient::campaignName(
            $customerId,
            $campaignId
        );
    }

    /**
     * Generates a resource name of campaign budget type.
     *
     * @param string $customerId
     * @param string $campaignBudgetId
     * @return string the campaign budget resource name
     */
    public static function forCampaignBudget(
        $customerId,
        $campaignBudgetId
    ): string {
        return CampaignBudgetServiceClient::campaignBudgetName(
            $customerId,
            $campaignBudgetId
        );
    }

    /**
     * Generates a resource name of campaign criterion type.
     *
     * @param string $customerId
     * @param string $campaignId
     * @param string $criterionId
     * @return string the campaign criterion resource name
     */
    public static function forCampaignCriterion(
        $customerId,
        $campaignId,
        $criterionId
    ): string {
        return CampaignCriterionServiceClient::campaignCriterionName(
            $customerId,
            $campaignId,
            $criterionId
        );
    }

    /**
     * Generates a resource name of conversion action type.
     *
     * @param string $customerId
     * @param string $conversionActionId
     * @return string the conversion action resource name
     */
    public static function forConversionAction(
        $customerId,
        $conversionActionId
    ): string {
        return ConversionActionServiceClient::conversionActionName(
            $customerId,
            $conversionActionId
        );
    }

    /**
     * Generates a resource name of customer type.
     *
     * @param string $customerId
     * @return string the customer resource name
     */
    public static function forCustomer(
        $customerId
    ): string {
        return CustomerServiceClient::customerName(
            $customerId
        );
    }

    /**
     * Generates a resource name of customer user access type.
     *
     * @param string $customerId
     * @param string $userId
     * @return string the customer user access resource name
     */
    public static function forCustomerUserAccess(
        $customerId,
        $userId
    ): string {
        return CustomerUserAccessServiceClient::customerUserAccessName(
            $customerId,
            $userId
        );
    }

    /**
     * Generates a resource name of geo target constant type.
     *
     * @param string $criterionId
     * @return string the geo target constant resource name
     */
    public static function forGeoTargetConstant(
        $criterionId
    ): string {
        $pathTemplate = new PathTemplate(
            'geoTargetConstants/{criterion_id}'
        );
        return $pathTemplate->render([
            'criterion_id' => $criterionId,
        ]);
    }

    /**
     * Generates a resource name of merchant center link type.
     *
     * @param string $customerId
     * @param string $merchantCenterId
     * @return string the merchant center link resource name
     */
    public static function forMerchantCenterLink(
        $customerId,
        $merchantCenterId
    ): string {
        return MerchantCenterLinkServiceClient::merchantCenterLinkName(
            $customerId,
            $merchantCenterId
        );
    }

    /**
     * Generates a resource name of shopping performance view type.
     *
     * @param string $customerId
     * @return string the shopping performance view resource name
     */
    public static function forShoppingPerformanceView(
        $customerId
    ): string {
        $pathTemplate = new PathTemplate(
            'customers/{customer_id}/shoppingPerformanceView'
        );
        return $pathTemplate->render([
            'customer_id' => $customerId,
        ]);
    }
}
