/**
 * External dependencies
 */
import { useState, createInterpolateElement } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { recordEvent } from '@woocommerce/tracks';
import {
	CardDivider,
	Flex,
	FlexBlock,
	Icon,
	__experimentalText as Text, // eslint-disable-line @wordpress/no-unsafe-wp-apis --- _experimentalText unlikely to change/disappear and also used by WC Core
} from '@wordpress/components';

/**
 * Internal dependencies
 */
import { useSettingsSelect } from '../../helpers/effects';
import AdsCreditsTermsAndConditionsModal from '../../components/TermsAndConditionsModal';
import GiftIcon from '../../components/GiftIcon';

const AdsCreditsPromo = () => {
	const appSettings = useSettingsSelect();
	const [
		isTermsAndConditionsModalOpen,
		setIsTermsAndConditionsModalOpen,
	] = useState( false );

	const openTermsAndConditionsModal = () => {
		setIsTermsAndConditionsModalOpen( true );
		recordEvent( 'pfw_modal_open', {
			context: 'wizard',
			name: 'ads-credits-terms-and-conditions',
		} );
	};

	const closeTermsAndConditionsModal = () => {
		setIsTermsAndConditionsModalOpen( false );
		recordEvent( 'pfw_modal_closed', {
			context: 'wizard',
			name: 'ads-credits-terms-and-conditions',
		} );
	};

	return appSettings?.ads_campaign_is_active ? (
		<>
			<CardDivider
				className={ 'woocommerce-setup-guide__ad-credits__divider' }
			/>
			<Flex className={ 'woocommerce-setup-guide__ad-credits' }>
				<FlexBlock className="image-block">
					<Icon icon={ GiftIcon } />
				</FlexBlock>
				<FlexBlock className="content-block">
					<Text variant="body">
						{ createInterpolateElement(
							__(
								'As a new Pinterest customer, you can get $125 in free ad credits when you successfully set up Pinterest for WooCommerce and spend $15 on Pinterest Ads. <a>Pinterest Terms and conditions</a> apply.',
								'pinterest-for-woocommerce'
							),
							{
								a: (
									// Disabling no-content rule - content is interpolated from above string
									// eslint-disable-next-line jsx-a11y/anchor-is-valid, jsx-a11y/anchor-has-content
									<a
										href={ '#' }
										onClick={ openTermsAndConditionsModal }
									/>
								),
							}
						) }
					</Text>
				</FlexBlock>
			</Flex>
			{ isTermsAndConditionsModalOpen && (
				<AdsCreditsTermsAndConditionsModal
					onModalClose={ closeTermsAndConditionsModal }
				/>
			) }
		</>
	) : null;
};

export default AdsCreditsPromo;
