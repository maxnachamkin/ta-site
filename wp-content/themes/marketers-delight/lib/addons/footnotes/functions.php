<?php

// Prevent direct access

if ( ! defined( 'ABSPATH' ) ) exit;

// Actions
add_shortcode( 'fn', 'md_footnotes_shortcode' );
add_filter( 'the_content', 'md_footnotes_list' );


/**
 * [fn] shortcode template.
 *
 * @since 1.0
 */

if ( ! function_exists( 'md_footnotes_shortcode' ) ) :

function md_footnotes_shortcode( $atts ) {
	extract( shortcode_atts( array(
		'id' => '',
	), $atts, 'footnote' ) );

	$id        = $atts['id'];
	$footnotes = get_post_meta( get_the_ID(), 'md_footnotes_footnotes', true );

	if ( empty( $footnotes[$id] ) )
		return;

	$after_post = get_post_meta( get_the_ID(), 'md_footnotes_after_post', true );
	$url        = get_permalink();
	$right      = ( $id % 2 == 0 ? ' right' : '' );

	ob_start();
?>

	<span id="footnote_<?php echo $id; ?>" class="md-footnote">

		<sup class="md-footnote-number"><?php echo $id; ?></sup>

		<cite class="md-footnote-text<?php echo $right; ?>">

			<span class="md-footnote-text-number"><?php echo ! empty( $after_post['show'] ) ? "<a href=\"{$url}#footnotes\">$id</a>" : $id; ?>.</span>

			<?php echo $footnotes[$id]['footnote']; ?>

			<span class="md-footnote-triggers">

				<?php if ( ! empty( $after_post['show'] ) ) : ?>
					<a href="#footnotes" class="md-footnote-trigger-list md-icon-menu"></a>
				<?php endif; ?>

				<span class="md-footnote-trigger-close">&times;</span>

			</span>

		</cite>

	</span>

	<?php wp_enqueue_script( 'md-footnotes', MD_FOOTNOTES_URL . 'footnotes.js' ); ?>

<?php return ob_get_clean(); }

endif;


/**
 * Generate footnotes list after post.
 *
 * @since 1.0
 */

if ( ! function_exists( 'md_footnotes_list' ) ) :

function md_footnotes_list( $content ) {
	$after_post = get_post_meta( get_the_ID(), 'md_footnotes_after_post', true );
	$footnotes  = get_post_meta( get_the_ID(), 'md_footnotes_footnotes', true );

	if ( ! empty( $after_post['show'] ) && ! empty( $footnotes[1] ) && is_singular() ) {
		$notes = '';
		$c     = 0;
		$url   = get_permalink();

		foreach ( $footnotes as $footnote ) {
			if ( ! empty( $footnote['footnote'] ) )
				$notes .= '<li>' . $footnote['footnote'] . " <a href=\"{$url}#footnote_{$c}\">&#8617;</a>" . '</li>';
			$c++;
		}

		$content .=
			'<div id="footnotes" class="md-footnotes format-text-sec text-sec links-sec">'.
			'<h4>' . apply_filters( 'md_footnotes_list_title', __( 'Footnotes', 'md' ) ) . '</h4>'.
			'<ol>'.
			$notes.
			'</ol>'.
			'</div>';
	}

	return $content;
}

endif;