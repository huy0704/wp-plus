<?php defined( 'ABSPATH' ) or exit; ?>

<?php $current_tab = $this->get_current_tab(); ?>

<div class="su-admin-tabs nav-tab-wrapper wp-clearfix">

	<?php foreach( $this->get_tabs() as $tab_id => $tab_title ) : ?>

		<?php if ( $tab_id === $current_tab ) : ?>
			<a href="<?php echo $this->get_tab_url( $tab_id ); ?>" class="nav-tab nav-tab-active"><?php echo $tab_title; ?></a>
		<?php else : ?>
			<a href="<?php echo $this->get_tab_url( $tab_id ); ?>" class="nav-tab"><?php echo $tab_title; ?></a>
		<?php endif; ?>

	<?php endforeach; ?>

</div>
