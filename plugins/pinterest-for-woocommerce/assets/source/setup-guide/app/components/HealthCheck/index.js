/**
 * External dependencies
 */
import { __ } from '@wordpress/i18n';
import { addQueryArgs } from '@wordpress/url';
import { useEffect, useState, useCallback } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import {
	Notice,
	__experimentalText as Text, // eslint-disable-line @wordpress/no-unsafe-wp-apis --- _experimentalText unlikely to change/disappear and also used by WC Core
} from '@wordpress/components';

/**
 * Internal dependencies
 */
import './style.scss';
import { useSettingsSelect, useCreateNotice } from '../../helpers/effects';

const DISAPPROVAL_COPY_STATES = {
	MARKETPLACE: __(
		'Merchant is an affiliate or resale marketplace',
		'pinterest-for-woocommerce'
	),
	PROHIBITED_PRODUCTS: __(
		'Merchant does not meet our policy on prohibited products',
		'pinterest-for-woocommerce'
	),
	SERVICES: __(
		'Merchant offers services rather than products',
		'pinterest-for-woocommerce'
	),
	DOMAIN_AGE: __(
		"Merchant's domain age does not meet minimum requirement",
		'pinterest-for-woocommerce'
	),
	DOMAIN_MISMATCH: __(
		'Merchant domain mismatched with merchant account',
		'pinterest-for-woocommerce'
	),
	BROKEN_URL: __(
		"Merchant's URL is broken or requires registration",
		'pinterest-for-woocommerce'
	),
	INCOMPLETE: __(
		"Merchant's URL is incomplete or inaccessible",
		'pinterest-for-woocommerce'
	),
	NO_SHIPPING_POLICY: __(
		"Merchant's shipping policy is unclear or unavailable",
		'pinterest-for-woocommerce'
	),
	NO_RETURN_POLICY: __(
		"Merchant's returns policy is unclear or unavailable",
		'pinterest-for-woocommerce'
	),
	AUTHENTICITY: __(
		"Merchant's information is incomplete or plagiarized",
		'pinterest-for-woocommerce'
	),
	IN_STOCK: __(
		"Merchant's products are out of stock",
		'pinterest-for-woocommerce'
	),
	BANNER_ADS: __(
		"Merchant's website includes banner or pop-up ads",
		'pinterest-for-woocommerce'
	),
	IMAGE_QUALITY: __(
		"Merchant's products do not meet image quality requirements",
		'pinterest-for-woocommerce'
	),
	WATERMARKS: __(
		"Merchant's product images include watermarks",
		'pinterest-for-woocommerce'
	),
	SALE: __(
		"Merchant's products are always on sale",
		'pinterest-for-woocommerce'
	),
	OUT_OF_DATE: __(
		"Merchant's products refer to outdated content",
		'pinterest-for-woocommerce'
	),
	PRODUCT_DESCRIPTION: __(
		"Merchant's website uses generic product descriptions",
		'pinterest-for-woocommerce'
	),
	POP_UP: __(
		"Merchant's website displays several pop-up messages",
		'pinterest-for-woocommerce'
	),
	INAUTHENTIC_PHOTOS: __(
		"Merchant's product images are unavailable or mismatched",
		'pinterest-for-woocommerce'
	),
	RESALE_MARKETPLACE: __(
		'Resale marketplaces are not allowed',
		'pinterest-for-woocommerce'
	),
	AFFILIATE_MARKETPLACE: __(
		'Affiliate links are not allowed',
		'pinterest-for-woocommerce'
	),
	WEBSITE_REQUIREMENTS: __(
		'Account does not meet the website requirements for verification',
		'pinterest-for-woocommerce'
	),
	PRODUCT_REQUIREMENTS: __(
		'Account does not meet the product requirements for verification',
		'pinterest-for-woocommerce'
	),
	BRAND_REPUTATION: __(
		'Account does not meet the brand reputation criteria for verification',
		'pinterest-for-woocommerce'
	),
};

const FormattedReasons = ( { reasons } ) => {
	if ( reasons === undefined ) {
		return null;
	}

	return (
		<ul className="ul-square">
			{ reasons.map( ( reason ) => (
				<li key={ reason }>{ DISAPPROVAL_COPY_STATES[ reason ] }</li>
			) ) }
		</ul>
	);
};

const HealthCheck = () => {
	const createNotice = useCreateNotice();
	const appSettings = useSettingsSelect();
	const [ healthStatus, setHealthStatus ] = useState();

	const checkHealth = useCallback( async () => {
		try {
			const results = await apiFetch( {
				path:
					wcSettings.pinterest_for_woocommerce.apiRoute + '/health/',
				method: 'GET',
			} );

			setHealthStatus( results );
		} catch ( error ) {
			createNotice(
				'error',
				error.message ||
					__(
						'Couldn’t retrieve the health status of your account.',
						'pinterest-for-woocommerce'
					)
			);
		}
	}, [ createNotice ] );

	useEffect( () => {
		checkHealth();
	}, [ checkHealth ] );

	if ( healthStatus === undefined || healthStatus.status === 'approved' ) {
		return null;
	}

	const notices = {
		pending_initial_configuration: {
			status: 'warning',
			message: __(
				'The feed is being configured. Depending on the number of products this may take a while as the feed needs to be fully generated before it is been sent to Pinterest for registration. You can check the status of the generation process in the Catalog tab.',
				'pinterest-for-woocommerce'
			),
			dismissible: false,
		},
		pending: {
			status: 'warning',
			message: __(
				'Please hold on tight as your account is pending approval from Pinterest. This may take up to 5 business days.',
				'pinterest-for-woocommerce'
			),
			dismissible: false,
		},
		declined: {
			status: 'error',
			message: __(
				'Your merchant account is disapproved.',
				'pinterest-for-woocommerce'
			),
			body: __(
				'If you have a valid reason for appealing a merchant review decision (such as having corrected the violations that resulted in the disapproval), you can submit an appeal.',
				'pinterest-for-woocommerce'
			),
			reasons: healthStatus.reasons,
			dismissible: false,
			actions: [
				{
					label: __(
						'Submit an appeal',
						'pinterest-for-woocommerce'
					),
					url: addQueryArgs(
						wcSettings.pinterest_for_woocommerce.pinterestLinks
							.appealDeclinedMerchant,
						{ advertiserId: appSettings.tracking_advertiser }
					),
				},
			],
		},
		appeal_pending: {
			status: 'warning',
			dismissible: false,
			message: __(
				'Your merchant account is disapproved.',
				'pinterest-for-woocommerce'
			),
			body: __(
				'Please hold on tight as there is an Appeal pending for your Pinterest account.',
				'pinterest-for-woocommerce'
			),
		},
		merchant_connected_diff_platform: {
			status: 'error',
			dismissible: false,
			message: __(
				'Unable to upload catalog.',
				'pinterest-for-woocommerce'
			),
			body: __(
				'It looks like your Pinterest business account is connected to another e-commerce platform. Only one platform can be linked to a business account. To upload your catalog, disconnect your business account from the other platform and try again.',
				'pinterest-for-woocommerce'
			),
		},
		merchant_locale_not_valid: {
			status: 'error',
			dismissible: false,
			message: __(
				'Unable to register feed.',
				'pinterest-for-woocommerce'
			),
			body: __(
				'It looks like your WordPress language settings are not supported by Pinterest.',
				'pinterest-for-woocommerce'
			),
		},
		error: {
			status: 'error',
			dismissible: false,
		},
	};

	const notice = notices[ healthStatus.status ] || notices.error;

	if ( notice.status === 'error' && ! notice.message ) {
		notice.message =
			healthStatus.message ||
			__(
				'Could not fetch account status.',
				'pinterest-for-woocommerce'
			);
	}

	return (
		<Notice
			status={ notice.status }
			isDismissible={ notice.dismissible }
			actions={ notice.actions || [] }
			className="pinterest-for-woocommerce-healthcheck-notice"
		>
			<Text variant="titleSmall">{ notice.message }</Text>
			<FormattedReasons reasons={ notice.reasons } />
			{ notice.body }
		</Notice>
	);
};

export default HealthCheck;
