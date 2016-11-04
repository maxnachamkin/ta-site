<?php $count = 0; ?>

<?php if ( have_comments() ) : ?>

	<!-- Title -->

	<p class="comments-title links-main mb-mid">
		<span class="middot"><i class="byline-item-icon md-icon md-icon-chat"></i> <?php echo sprintf( _nx( '1 comment', '%1$s comments', get_comments_number(), 'comments title', 'md' ), number_format_i18n( get_comments_number() ) ); ?></span>

		<?php echo ( comments_open() ? '<a href="#respond">' . __( 'add yours', 'md' ) . '</a>' : '<span class="comments-closed">' . __( 'comments closed', 'md' ) . '</span>' ); ?>
	</p>

	<!-- Comments List -->

	<div class="comments-area">

		<ol class="comments-list">
			<?php wp_list_comments( array(
				'type'        => 'comment',
				'callback'    => 'md_comment',
				'avatar_size' => 52
			) ); ?>
		</ol>

		<!-- Pagination -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>

			<div class="pagination block-single mt-single links-sec">
				<?php paginate_comments_links( array(
					'prev_text' => '<i class="byline-item-icon md-icon md-icon-angle-left"></i> ' . __( 'Previous', 'md' ),
					'next_text' => __( 'Next', 'md' ) . ' <i class="byline-item-icon md-icon md-icon-angle-right"></i>',
				) ); ?>
			</div>

		<?php endif; ?>

	</div>

<?php endif; ?>

<!-- Comment Form -->

<?php comment_form( array(
	'title_reply'          => __( 'Leave a Comment', 'md' ),
	'comment_notes_before' => false,
	'comment_notes_after'  => false,
	'logged_in_as'         => false,
	'cancel_reply_link'    => __( 'Cancel', 'md')
) ); ?>