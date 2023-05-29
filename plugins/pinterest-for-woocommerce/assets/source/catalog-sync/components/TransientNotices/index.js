/**
 * External dependencies
 */
import classnames from 'classnames';
import { SnackbarList } from '@wordpress/components';
import { compose } from '@wordpress/compose';
import { withDispatch, withSelect } from '@wordpress/data';

/**
 * Internal dependencies
 */
import './style.scss';

const TransientNotices = ( { className, notices, onRemove } ) => {
	const classes = classnames(
		'woocommerce-transient-notices',
		'components-notices__snackbar',
		className
	);

	return (
		<SnackbarList
			notices={ notices }
			className={ classes }
			onRemove={ onRemove }
		/>
	);
};

export default compose(
	withSelect( ( select ) => {
		const notices = select( 'core/notices' ).getNotices();

		return { notices };
	} ),
	withDispatch( ( dispatch ) => ( {
		onRemove: dispatch( 'core/notices' ).removeNotice,
	} ) )
)( TransientNotices );
