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

<div class="su-posts su-posts-default-loop <?php echo esc_attr( $atts['class'] ); ?>">

	<?php if ( $posts->have_posts() ) : ?>

		<?php while ( $posts->have_posts() ) : ?>
			<?php $posts->the_post(); ?>

			<?php if ( ! su_current_user_can_read_post( get_the_ID() ) ) : ?>
				<?php continue; ?>
			<?php endif; ?>

			<div id="su-post-<?php the_ID(); ?>" class="su-post <?php echo esc_attr( $atts['class_single'] ); ?>">

				<?php if ( has_post_thumbnail( get_the_ID() ) ) : ?>
					<a class="su-post-thumbnail" href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
				<?php endif; ?>

				<h2 class="su-post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

				<div class="su-post-meta">
					<?php esc_html_e( 'Posted', 'shortcodes-ultimate' ); ?>: <?php the_time( get_option( 'date_format' ) ); ?>
				</div>

				<div class="su-post-excerpt">
					<?php the_excerpt(); ?>
				</div>

				<?php if ( have_comments() || comments_open() ) : ?>
					<a href="<?php comments_link(); ?>" class="su-post-comments-link"><?php comments_number( __( '0 comments', 'shortcodes-ultimate' ), __( '1 comment', 'shortcodes-ultimate' ), '% comments' ); ?></a>
				<?php endif; ?>

			</div>

		<?php endwhile; ?>

	<?php else : ?>
		<h4><?php esc_html_e( 'Posts not found', 'shortcodes-ultimate' ); ?></h4>
	<?php endif; ?>

</div>
