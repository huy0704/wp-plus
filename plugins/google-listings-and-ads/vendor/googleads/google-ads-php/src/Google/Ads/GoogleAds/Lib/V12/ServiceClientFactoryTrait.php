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

namespace Google\Ads\GoogleAds\Lib\V12;

use Google\Ads\GoogleAds\Constants;
use Google\Ads\GoogleAds\Lib\ConfigurationTrait;
use Google\Ads\GoogleAds\Lib\InsecureCredentialsWrapper;
use Google\Ads\GoogleAds\V12\Services\AccountLinkServiceClient;
use Google\Ads\GoogleAds\V12\Services\AdGroupAdServiceClient;
use Google\Ads\GoogleAds\V12\Services\AdGroupCriterionServiceClient;
use Google\Ads\GoogleAds\V12\Services\AdGroupServiceClient;
use Google\Ads\GoogleAds\V12\Services\AdServiceClient;
use Google\Ads\GoogleAds\V12\Services\AssetGroupAssetServiceClient;
use Google\Ads\GoogleAds\V12\Services\AssetGroupListingGroupFilterServiceClient;
use Google\Ads\GoogleAds\V12\Services\AssetGroupServiceClient;
use Google\Ads\GoogleAds\V12\Services\AssetServiceClient;
use Google\Ads\GoogleAds\V12\Services\BillingSetupServiceClient;
use Google\Ads\GoogleAds\V12\Services\CampaignBudgetServiceClient;
use Google\Ads\GoogleAds\V12\Services\CampaignCriterionServiceClient;
use Google\Ads\GoogleAds\V12\Services\CampaignServiceClient;
use Google\Ads\GoogleAds\V12\Services\ConversionActionServiceClient;
use Google\Ads\GoogleAds\V12\Services\CustomerServiceClient;
use Google\Ads\GoogleAds\V12\Services\CustomerUserAccessServiceClient;
use Google\Ads\GoogleAds\V12\Services\GeoTargetConstantServiceClient;
use Google\Ads\GoogleAds\V12\Services\GoogleAdsServiceClient;
use Google\Ads\GoogleAds\V12\Services\MerchantCenterLinkServiceClient;
use Google\ApiCore\GrpcSupportTrait;
use Grpc\ChannelCredentials;

/**
 * Contains service client factory methods.
 */
trait ServiceClientFactoryTrait
{
    use ConfigurationTrait;
    use GrpcSupportTrait;

    private static $CREDENTIALS_LOADER_KEY = 'credentials';
    private static $DEVELOPER_TOKEN_KEY = 'developer-token';
    private static $LOGIN_CUSTOMER_ID_KEY = 'login-customer-id';
    private static $LINKED_CUSTOMER_ID_KEY = 'linked-customer-id';
    private static $SERVICE_ADDRESS_KEY = 'serviceAddress';
    private static $DEFAULT_SERVICE_ADDRESS = 'googleads.googleapis.com';
    private static $TRANSPORT_KEY = 'transport';
    private static $UNARY_MIDDLEWARES = 'unary-middlewares';
    private static $STREAMING_MIDDLEWARES = 'streaming-middlewares';

    /**
     * Gets the Google Ads client options for making API calls.
     *
     * @return array the client options
     */
    public function getGoogleAdsClientOptions(): array
    {
        $clientOptions = [
            self::$CREDENTIALS_LOADER_KEY => $this->getGrpcChannelIsSecure()
                ? $this->getOAuth2Credential()
                : new InsecureCredentialsWrapper($this->getOAuth2Credential()),
            self::$DEVELOPER_TOKEN_KEY => $this->getDeveloperToken()
        ];
        if (!empty($this->getLoginCustomerId())) {
            $clientOptions += [self::$LOGIN_CUSTOMER_ID_KEY => strval($this->getLoginCustomerId())];
        }
        if (!empty($this->getLinkedCustomerId())) {
            $clientOptions += [
                self::$LINKED_CUSTOMER_ID_KEY => strval($this->getLinkedCustomerId())
            ];
        }
        if (!empty($this->getEndpoint())) {
            $clientOptions += [self::$SERVICE_ADDRESS_KEY => $this->getEndpoint()];
        }
        $clientOptions['libName'] = Constants::LIBRARY_NAME;
        $clientOptions['libVersion'] = Constants::LIBRARY_VERSION;
        $clientOptions['transportConfig'] = [
            'grpc' => [
                'stubOpts' => [
                    // Inbound headers may exceed default (8kb) max header size.
                    // Sets max header size to 16MB, which should be more than necessary.
                    'grpc.max_metadata_size' => 16 * 1024 * 1024,
                    // Sets max response size to 64MB, since large responses will often exceed the
                    // default (4MB).
                    'grpc.max_receive_message_length' => 64 * 1024 * 1024
                ],
                'interceptors' => [new GoogleAdsFailuresInterceptor()]
            ]
        ];
        if (!empty($this->getLogger())) {
            $googleAdsLoggingInterceptor = new GoogleAdsLoggingInterceptor(
                new GoogleAdsCallLogger(
                    $this->getLogger(),
                    $this->getLogLevel(),
                    $this->getEndpoint() ?: self::$DEFAULT_SERVICE_ADDRESS
                )
            );
            array_unshift(
                $clientOptions['transportConfig']['grpc']['interceptors'],
                $googleAdsLoggingInterceptor
            );
        }
        array_push(
            $clientOptions['transportConfig']['grpc']['interceptors'],
            ...$this->getGrpcInterceptors()
        );
        if (!empty($this->getProxy())) {
            putenv('http_proxy=' . $this->getProxy());
        }
        if (!empty($this->getTransport())) {
            $clientOptions += [self::$TRANSPORT_KEY => $this->getTransport()];
        }
        if (
            self::getGrpcDependencyStatus()
            && (!$this->getGrpcChannelIsSecure() || !empty($this->getGrpcChannelCredential()))
        ) {
            $channelCredentials = $this->getGrpcChannelIsSecure()
                ? $this->getGrpcChannelCredential()
                : ChannelCredentials::createInsecure();
            $clientOptions['transportConfig']['grpc']['stubOpts'] += [
                self::$CREDENTIALS_LOADER_KEY => $channelCredentials
            ];
        }
        $clientOptions += [
            self::$UNARY_MIDDLEWARES => $this->getUnaryMiddlewares(),
            self::$STREAMING_MIDDLEWARES => $this->getStreamingMiddlewares()
        ];

        return $clientOptions;
    }

    /**
     * @return AccountLinkServiceClient
     */
    public function getAccountLinkServiceClient(): AccountLinkServiceClient
    {
        return new AccountLinkServiceClient($this->getGoogleAdsClientOptions());
    }

    /**
     * @return AdGroupAdLabelServiceClient
     */
    public function getAdGroupAdLabelServiceClient(): AdGroupAdLabelServiceClient
    {
        return new AdGroupAdLabelServiceClient($this->getGoogleAdsClientOptions());
    }

    /**
     * @return AdGroupAdServiceClient
     */
    public function getAdGroupAdServiceClient(): AdGroupAdServiceClient
    {
        return new AdGroupAdServiceClient($this->getGoogleAdsClientOptions());
    }

    /**
     * @return AdGroupCriterionServiceClient
     */
    public function getAdGroupCriterionServiceClient(): AdGroupCriterionServiceClient
    {
        return new AdGroupCriterionServiceClient($this->getGoogleAdsClientOptions());
    }

    /**
     * @return AdGroupServiceClient
     */
    public function getAdGroupServiceClient(): AdGroupServiceClient
    {
        return new AdGroupServiceClient($this->getGoogleAdsClientOptions());
    }

    /**
     * @return AdServiceClient
     */
    public function getAdServiceClient(): AdServiceClient
    {
        return new AdServiceClient($this->getGoogleAdsClientOptions());
    }

    /**
     * @return AssetGroupAssetServiceClient
     */
    public function getAssetGroupAssetServiceClient(): AssetGroupAssetServiceClient
    {
        return new AssetGroupAssetServiceClient($this->getGoogleAdsClientOptions());
    }

    /**
     * @return AssetGroupListingGroupFilterServiceClient
     */
    public function getAssetGroupListingGroupFilterServiceClient(): AssetGroupListingGroupFilterServiceClient
    {
        return new AssetGroupListingGroupFilterServiceClient($this->getGoogleAdsClientOptions());
    }

    /**
     * @return AssetGroupServiceClient
     */
    public function getAssetGroupServiceClient(): AssetGroupServiceClient
    {
        return new AssetGroupServiceClient($this->getGoogleAdsClientOptions());
    }

    /**
     * @return AssetServiceClient
     */
    public function getAssetServiceClient(): AssetServiceClient
    {
        return new AssetServiceClient($this->getGoogleAdsClientOptions());
    }

    /**
     * @return BillingSetupServiceClient
     */
    public function getBillingSetupServiceClient(): BillingSetupServiceClient
    {
        return new BillingSetupServiceClient($this->getGoogleAdsClientOptions());
    }

    /**
     * @return CampaignBudgetServiceClient
     */
    public function getCampaignBudgetServiceClient(): CampaignBudgetServiceClient
    {
        return new CampaignBudgetServiceClient($this->getGoogleAdsClientOptions());
    }

    /**
     * @return CampaignCriterionServiceClient
     */
    public function getCampaignCriterionServiceClient(): CampaignCriterionServiceClient
    {
        return new CampaignCriterionServiceClient($this->getGoogleAdsClientOptions());
    }

    /**
     * @return CampaignServiceClient
     */
    public function getCampaignServiceClient(): CampaignServiceClient
    {
        return new CampaignServiceClient($this->getGoogleAdsClientOptions());
    }

    /**
     * @return ConversionActionServiceClient
     */
    public function getConversionActionServiceClient(): ConversionActionServiceClient
    {
        return new ConversionActionServiceClient($this->getGoogleAdsClientOptions());
    }

    /**
     * @return CustomerServiceClient
     */
    public function getCustomerServiceClient(): CustomerServiceClient
    {
        return new CustomerServiceClient($this->getGoogleAdsClientOptions());
    }

    /**
     * @return CustomerUserAccessServiceClient
     */
    public function getCustomerUserAccessServiceClient(): CustomerUserAccessServiceClient
    {
        return new CustomerUserAccessServiceClient($this->getGoogleAdsClientOptions());
    }

    /**
     * @return GeoTargetConstantServiceClient
     */
    public function getGeoTargetConstantServiceClient(): GeoTargetConstantServiceClient
    {
        return new GeoTargetConstantServiceClient($this->getGoogleAdsClientOptions());
    }

    /**
     * @return GoogleAdsServiceClient
     */
    public function getGoogleAdsServiceClient(): GoogleAdsServiceClient
    {
        return new GoogleAdsServiceClient($this->getGoogleAdsClientOptions());
    }

    /**
     * @return MerchantCenterLinkServiceClient
     */
    public function getMerchantCenterLinkServiceClient(): MerchantCenterLinkServiceClient
    {
        return new MerchantCenterLinkServiceClient($this->getGoogleAdsClientOptions());
    }
}
