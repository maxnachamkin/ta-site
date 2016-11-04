<?php
	$GLOBALS['comment'] = $comment;
	$link               = get_author_posts_url( get_the_author_meta( 'ID' ) );
?>

<li id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? 'block-mid mb-mid' : 'mb-mid block-mid parent' ); ?>>

	<article class="comment-body">

		<!-- Byline -->

		<footer class="comment-byline byline text-sec links-sec mb-single clear">

			<?php if ( $args['avatar_size'] != 0 ): ?>
				<div class="byline-avatar"><?php echo get_avatar( $comment, $args['avatar_size'] ); ?></div>
			<?php endif; ?>

			<div class="byline-author">
				<?php echo sprintf( __( '<span class="caps font-main">%s</span> <em>says:</em>', 'md' ), get_comment_author_link() ); ?>
			</div>

			<div class="byline-date">

				<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
					<time datetime="<?php comment_time( 'c' ); ?>">
						<?php comment_date(); ?>
					</time>
				</a>

			</div>

		</footer>

		<!-- Content -->

		<div class="comment-content-wrap">

			<div id="comment-content-<?php comment_ID(); ?>" class="comment-content mb-mid links-main">

				<?php if ( $comment->comment_approved == 0 ) : ?>
					<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'md' ); ?></p>
				<?php endif; ?>

				<?php comment_text(); ?>

			</div>

			<!-- Actions -->

			<div class="comment-reply-actions links-sec">

				<?php comment_reply_link( array_merge( $args, array(
					'add_below'  => 'comment-content',
					'depth'      => $depth,
					'max_depth'  => $args['max_depth'],
					'reply_text' => __( 'reply', 'md' ),
					'before'     => '<span class="comment-reply font-main caps">',
					'after'      => '</span>'
				) ) ); ?>

				<?php edit_comment_link( '<i class="md-icon md-icon-pencil"></i>', '<small class="byline-edit">', '</small>' ); ?>

			</div>

		</div>

	</article>