/**
 * External dependencies
 */
import { __ } from '@wordpress/i18n';
import { createInterpolateElement } from '@wordpress/element';
import {
	Modal,
	__experimentalText as Text, // eslint-disable-line @wordpress/no-unsafe-wp-apis --- _experimentalText unlikely to change/disappear and also used by WC Core
} from '@wordpress/components';

/**
 * Internal dependencies
 */
import documentationLinkProps from '../helpers/documentation-link-props';

const tosHref = 'https://business.pinterest.com/business-terms-of-service/';
const privacyPolicyHref = 'https://policy.pinterest.com/privacy-policy';
const advertisingServicesAgreementHref =
	'https://business.pinterest.com/pinterest-advertising-services-agreement/';

/**
 * Modal used for displaying terms and conditions information required for Ads Credit Feature.
 *
 * @param {Function} onModalClose Action to call when the modal gets closed.
 *
 * @fires wcadmin_pfw_documentation_link_click with `{ link_id: 'terms-of-service', context: 'ads-credits-terms-and-conditions' }`
 * @fires wcadmin_pfw_documentation_link_click with `{ link_id: 'privacy-policy', context: 'ads-credits-terms-and-conditions' }`
 * @fires wcadmin_pfw_documentation_link_click with `{ link_id: 'advertising-services-agreement', context: 'ads-credits-terms-and-conditions' }`
 *
 * @return {JSX.Element} Rendered element.
 */
const AdsCreditsTermsAndConditionsModal = ( { onModalClose } ) => {
	return (
		<Modal
			title={
				<>
					{ __(
						'Pinterest Terms & Conditions',
						'pinterest-for-woocommerce'
					) }
				</>
			}
			onRequestClose={ onModalClose }
			className="pinterest-for-woocommerce-landing-page__credits-section__tac-modal"
		>
			<Text>
				{ __(
					'To be eligible and redeem the $125 ad credit from Pinterest, you must complete the setup of Pinterest for WooCommerce, set up your billing with Pinterest Ads manager, and spend $15 with Pinterest ads. Credits may take up to 24 hours to be credited to the user.',
					'pinterest-for-woocommerce'
				) }
			</Text>
			<Text>
				{ __(
					'Each user is only eligible to receive the credits once. Ad credits may vary by country and is subject to availability.',
					'pinterest-for-woocommerce'
				) }
			</Text>
			<Text variant="body" isBlock>
				{ __(
					'The following terms and conditions apply:',
					'pinterest-for-woocommerce'
				) }
			</Text>
			{ createInterpolateElement(
				__(
					'<link>Business Terms of Service</link>',
					'pinterest-for-woocommerce'
				),
				{
					link: (
						// eslint-disable-next-line jsx-a11y/anchor-has-content -- context passed via documentationLinkProps
						<a
							{ ...documentationLinkProps( {
								href: tosHref,
								linkId: 'terms-of-service',
								context: 'ads-credits-terms-and-conditions',
							} ) }
						/>
					),
				}
			) }
			{ createInterpolateElement(
				__(
					'<link>Privacy Policy</link>',
					'pinterest-for-woocommerce'
				),
				{
					link: (
						// eslint-disable-next-line jsx-a11y/anchor-has-content -- context passed via documentationLinkProps
						<a
							{ ...documentationLinkProps( {
								href: privacyPolicyHref,
								linkId: 'privacy-policy',
								context: 'ads-credits-terms-and-conditions',
							} ) }
						/>
					),
				}
			) }
			{ createInterpolateElement(
				__(
					'<link>Pinterest Advertising Services Agreement</link>',
					'pinterest-for-woocommerce'
				),
				{
					link: (
						// eslint-disable-next-line jsx-a11y/anchor-has-content -- context passed via documentationLinkProps
						<a
							{ ...documentationLinkProps( {
								href: advertisingServicesAgreementHref,
								linkId: 'advertising-services-agreement',
								context: 'ads-credits-terms-and-conditions',
							} ) }
						/>
					),
				}
			) }
		</Modal>
	);
};

export default AdsCreditsTermsAndConditionsModal;
