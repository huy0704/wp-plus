<?php defined( 'ABSPATH' ) || exit; ?>

<?php
/**
 * READ BEFORE EDITING!
 *
 * Do not edit templates in the plugin folder, since all your changes will be
 * lost after the plugin update. Read the following article to learn how to
 * change this template or create a custom one:
 *
 * https://getshortcodes.com/docs/posts/#built-in-templates
 */
?>

<ul class="su-posts su-posts-list-loop <?php echo esc_attr( $atts['class'] ); ?>">

	<?php if ( $posts->have_posts() ) : ?>
		<?php while ( $posts->have_posts() ) : ?>
			<?php $posts->the_post(); ?>

			<?php if ( ! su_current_user_can_read_post( get_the_ID() ) ) : ?>
				<?php continue; ?>
			<?php endif; ?>

			<li id="su-post-<?php the_ID(); ?>" class="su-post <?php echo esc_attr( $atts['class_single'] ); ?>">
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</li>

		<?php endwhile; ?>
	<?php else : ?>

		<li><?php esc_html_e( 'Posts not found', 'shortcodes-ultimate' ); ?></li>

	<?php endif; ?>

</ul>
