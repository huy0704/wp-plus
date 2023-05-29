<?php defined( 'ABSPATH' ) || exit; ?>

<textarea name="<?php echo esc_attr( $data['id'] ); ?>" id="<?php echo esc_attr( $data['id'] ); ?>" cols="50" rows="15" class="large-text"><?php echo esc_textarea( get_option( $data['id'] ) ); ?></textarea>

<script type="text/javascript">
jQuery(function() {
	if (typeof wp === 'undefined' || typeof wp.codeEditor === 'undefined') {
		return;
	}

	var editorSettings = wp.codeEditor.defaultSettings
		? _.clone(wp.codeEditor.defaultSettings)
		: {};

	editorSettings.codemirror = _.extend({}, editorSettings.codemirror, {
		viewportMargin: Infinity
	});

	wp.codeEditor.initialize(
		"<?php echo esc_attr( $data['id'] ); ?>",
		editorSettings
	);
});
</script>

<p class="description"><?php echo esc_html( $data['description'] ); ?></p>

<details>
	<summary class="title"><?php esc_html_e( 'Available variables', 'shortcodes-ultimate' ); ?></summary>
	<article>
		<table class="widefat striped" style="width:auto">
			<thead>
				<tr>
					<td><?php esc_html_e( 'Variable', 'shortcodes-ultimate' ); ?></td>
					<td><?php esc_html_e( 'Will be replaced with', 'shortcodes-ultimate' ); ?>&hellip;</td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><code contenteditable>%home_url%</code></td>
					<td><?php printf( '%s (%s)', esc_html__( 'the URL of the site home page', 'shortcodes-ultimate' ), esc_html__( 'with trailing slash', 'shortcodes-ultimate' ) ); ?></td>
				</tr>
				<tr>
					<td><code contenteditable>%theme_url%</code></td>
					<td><?php printf( '%s (%s)', esc_html__( 'the URL of the directory of the current theme', 'shortcodes-ultimate' ), esc_html__( 'with trailing slash', 'shortcodes-ultimate' ) ); ?></td>
				</tr>
				<tr>
					<td><code contenteditable>%plugin_url%</code></td>
					<td><?php printf( '%s (%s)', esc_html__( 'the URL of the directory of the plugin', 'shortcodes-ultimate' ), esc_html__( 'with trailing slash', 'shortcodes-ultimate' ) ); ?></td>
				</tr>
			</tbody>
		</table>
	</article>
</details>

<details>
	<summary><?php esc_html_e( 'More information', 'shortcodes-ultimate' ); ?></summary>
	<article>
		<ul class="ul-disc">
			<?php // Translators: %s - link to the shortcodes.full.css file ?>
			<li><?php printf( esc_html__( 'Open %s file to see default styles', 'shortcodes-ultimate' ), '<a href="https://plugins.trac.wordpress.org/browser/shortcodes-ultimate/trunk/includes/css/shortcodes.full.css" target="_blank">shortcodes.full.css</a>' ); ?></li>
			<li><?php esc_html_e( 'Help article', 'shortcodes-ultimate' ); ?>: <a href="https://getshortcodes.com/docs/how-to-use-custom-css-editor/" target="_blank"><?php esc_html_e( 'How to use Custom CSS editor', 'shortcodes-ultimate' ); ?></a></li>
		</ul>
	</article>
</details>
