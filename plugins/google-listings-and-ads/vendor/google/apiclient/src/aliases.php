<?php

if (class_exists('Google_Client', false)) {
    // Prevent error with preloading in PHP 7.4
    // @see https://github.com/googleapis/google-api-php-client/issues/1976
    return;
}

$classMap = [
    'Google\\Client' => 'Google_Client',
    'Google\\Service' => 'Google_Service',
    'Google\\AccessToken\\Revoke' => 'Google_AccessToken_Revoke',
    'Google\\AccessToken\\Verify' => 'Google_AccessToken_Verify',
    'Google\\Model' => 'Google_Model',
    'Google\\Utils\\UriTemplate' => 'Google_Utils_UriTemplate',
    'Google\\AuthHandler\\Guzzle6AuthHandler' => 'Google_AuthHandler_Guzzle6AuthHandler',
    'Google\\AuthHandler\\Guzzle7AuthHandler' => 'Google_AuthHandler_Guzzle7AuthHandler',
    'Google\\AuthHandler\\Guzzle5AuthHandler' => 'Google_AuthHandler_Guzzle5AuthHandler',
    'Google\\AuthHandler\\AuthHandlerFactory' => 'Google_AuthHandler_AuthHandlerFactory',
    'Google\\Http\\Batch' => 'Google_Http_Batch',
    'Google\\Http\\MediaFileUpload' => 'Google_Http_MediaFileUpload',
    'Google\\Http\\REST' => 'Google_Http_REST',
    'Google\\Task\\Retryable' => 'Google_Task_Retryable',
    'Google\\Task\\Exception' => 'Google_Task_Exception',
    'Google\\Task\\Runner' => 'Google_Task_Runner',
    'Google\\Collection' => 'Google_Collection',
    'Google\\Service\\Exception' => 'Google_Service_Exception',
    'Google\\Service\\Resource' => 'Google_Service_Resource',
    'Google\\Exception' => 'Google_Exception',
];

foreach ($classMap as $class => $alias) {
    class_alias($class, $alias);
}

/**
 * This class needs to be defined explicitly as scripts must be recognized by
 * the autoloader.
 */
class Google_Task_Composer extends \Automattic\WooCommerce\GoogleListingsAndAds\Vendor\Google\Task\Composer
{
}

/** @phpstan-ignore-next-line */
if (\false) {
    class Google_AccessToken_Revoke extends \Automattic\WooCommerce\GoogleListingsAndAds\Vendor\Google\AccessToken\Revoke
    {
    }
    class Google_AccessToken_Verify extends \Automattic\WooCommerce\GoogleListingsAndAds\Vendor\Google\AccessToken\Verify
    {
    }
    class Google_AuthHandler_AuthHandlerFactory extends \Automattic\WooCommerce\GoogleListingsAndAds\Vendor\Google\AuthHandler\AuthHandlerFactory
    {
    }
    class Google_AuthHandler_Guzzle5AuthHandler extends \Automattic\WooCommerce\GoogleListingsAndAds\Vendor\Google\AuthHandler\Guzzle5AuthHandler
    {
    }
    class Google_AuthHandler_Guzzle6AuthHandler extends \Automattic\WooCommerce\GoogleListingsAndAds\Vendor\Google\AuthHandler\Guzzle6AuthHandler
    {
    }
    class Google_AuthHandler_Guzzle7AuthHandler extends \Automattic\WooCommerce\GoogleListingsAndAds\Vendor\Google\AuthHandler\Guzzle7AuthHandler
    {
    }
    class Google_Client extends \Automattic\WooCommerce\GoogleListingsAndAds\Vendor\Google\Client
    {
    }
    class Google_Collection extends \Automattic\WooCommerce\GoogleListingsAndAds\Vendor\Google\Collection
    {
    }
    class Google_Exception extends \Automattic\WooCommerce\GoogleListingsAndAds\Vendor\Google\Exception
    {
    }
    class Google_Http_Batch extends \Automattic\WooCommerce\GoogleListingsAndAds\Vendor\Google\Http\Batch
    {
    }
    class Google_Http_MediaFileUpload extends \Automattic\WooCommerce\GoogleListingsAndAds\Vendor\Google\Http\MediaFileUpload
    {
    }
    class Google_Http_REST extends \Automattic\WooCommerce\GoogleListingsAndAds\Vendor\Google\Http\REST
    {
    }
    class Google_Model extends \Automattic\WooCommerce\GoogleListingsAndAds\Vendor\Google\Model
    {
    }
    class Google_Service extends \Automattic\WooCommerce\GoogleListingsAndAds\Vendor\Google\Service
    {
    }
    class Google_Service_Exception extends \Automattic\WooCommerce\GoogleListingsAndAds\Vendor\Google\Service\Exception
    {
    }
    class Google_Service_Resource extends \Automattic\WooCommerce\GoogleListingsAndAds\Vendor\Google\Service\Resource
    {
    }
    class Google_Task_Exception extends \Automattic\WooCommerce\GoogleListingsAndAds\Vendor\Google\Task\Exception
    {
    }
    interface Google_Task_Retryable extends \Automattic\WooCommerce\GoogleListingsAndAds\Vendor\Google\Task\Retryable
    {
    }
    class Google_Task_Runner extends \Automattic\WooCommerce\GoogleListingsAndAds\Vendor\Google\Task\Runner
    {
    }
    class Google_Utils_UriTemplate extends \Automattic\WooCommerce\GoogleListingsAndAds\Vendor\Google\Utils\UriTemplate
    {
    }
}
