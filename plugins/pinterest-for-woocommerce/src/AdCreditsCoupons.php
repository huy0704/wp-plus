<?php
/**
 * Pinterest for WooCommerce Ads Credits Coupons
 *
 * @package     Pinterest_For_WooCommerce/Classes/
 * @version     1.2.5
 */

namespace Automattic\WooCommerce\Pinterest;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Handling ad credits.
 */
class AdCreditsCoupons {

	/**
	 * @var array $currency_coupons_map Mapping of coupons to currency available for that currency.
	 */
	public static $currency_coupons_map = array(
		'USD' => 'Q09JTl9DTElFTlRfSURfMTQ2ODQxNF9DUkVESVRT',
		'GBP' => 'Q09JTl9DTElFTlRfSURfMTQ2ODQxNF9DUkVESVRT',
		'EUR' => 'Q09JTl9DTElFTlRfSURfMTQ2ODQxNF9DUkVESVRT',
		'BRL' => 'Q09JTl9DTElFTlRfSURfMTQ2ODQxNF9DUkVESVRT',
		'AUD' => 'Q09JTl9DTElFTlRfSURfMTQ2ODQxNF9DUkVESVRT',
		'CAD' => 'Q09JTl9DTElFTlRfSURfMTQ2ODQxNF9DUkVESVRT',
		'MXN' => 'Q09JTl9DTElFTlRfSURfMTQ2ODQxNF9DUkVESVRT',
		'PLN' => 'Q09JTl9DTElFTlRfSURfMTQ2ODQxNF9DUkVESVRT',
		'CHF' => 'Q09JTl9DTElFTlRfSURfMTQ2ODQxNF9DUkVESVRT',
		'DKK' => 'Q09JTl9DTElFTlRfSURfMTQ2ODQxNF9DUkVESVRT',
		'RON' => 'Q09JTl9DTElFTlRfSURfMTQ2ODQxNF9DUkVESVRT',
		'SEK' => 'Q09JTl9DTElFTlRfSURfMTQ2ODQxNF9DUkVESVRT',
		'NZD' => 'Q09JTl9DTElFTlRfSURfMTQ2ODQxNF9DUkVESVRT',
		'HUF' => 'Q09JTl9DTElFTlRfSURfMTQ2ODQxNF9DUkVESVRT',
		'NOK' => 'Q09JTl9DTElFTlRfSURfMTQ2ODQxNF9DUkVESVRT',
		'JPY' => 'Q09JTl9DTElFTlRfSURfMTQ2ODQxNF9DUkVESVRT',
		'CZK' => 'Q09JTl9DTElFTlRfSURfMTQ2ODQxNF9DUkVESVRT',
		'ARS' => 'Q09JTl9DTElFTlRfSURfMTQ2ODQxNF9DUkVESVRT',
	);

	/**
	 * Get a valid coupon for merchant.
	 *
	 * @since 1.2.5
	 *
	 * @return string|false Coupon string of false if no coupon was found.
	 */
	public static function get_coupon_for_merchant() {
		$currency = get_woocommerce_currency();
		return self::$currency_coupons_map[ $currency ] ?? false;
	}

	/**
	 * Check if there is a valid coupon for the user currency.
	 *
	 * @return bool Wether there is a valid coupon for the merchant.
	 */
	public static function has_valid_coupon_for_merchant() {
		return self::get_coupon_for_merchant() !== false;
	}

}
