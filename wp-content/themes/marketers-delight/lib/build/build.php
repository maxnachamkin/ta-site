<?php
/**
 * The functions below load template HTML from the /content/ folder.
 * These functions are then hooked into their respective locations throughout
 * the theme by hooks below. These can be unhooked with a child theme, OR:
 *
 * You can edit any of these templates (without unhooking) by opening any file from
 * the /content/ folder and copying and pasting the contents into a child
 * theme with the same file path.
 */

// load other layout elements
require_once( MD_DIR . 'lib/build/hooks.php' );
require_once( MD_DIR . 'lib/build/filters.php' );

// Build header
add_action( 'md_hook_header', 'md_logo', 10 );
add_action( 'md_hook_header', 'md_menu', 20 );

// Build content box
add_action( 'md_hook_content', 'md_archives_title', 5 );
add_action( 'md_hook_content', 'md_loop', 10 );
add_action( 'md_hook_content', 'md_pagination', 90 );
add_action( 'md_hook_content', 'md_comments', 80 );
add_action( 'md_hook_content', 'md_post_nav', 90 );

// Build content item (post / page structure)
add_action( 'md_hook_content_item', 'md_content_schema' );
add_action( 'md_hook_content_item', 'md_content_headline', 10 );
add_action( 'md_hook_before_headline', 'md_byline' );
add_action( 'md_hook_content_item', 'md_content_text', 20 );
add_action( 'md_hook_content_item', 'md_featured_image_above_headline', 5 );
add_action( 'md_hook_content_item', 'md_featured_image_below_headline', 15 );

// Build footer
add_action( 'md_hook_footer', 'md_footer_columns_template' );
add_action( 'md_hook_footer', 'md_footer_copy' );


/**
 * Displays the logo, used in header by default.
 *
 * @since 4.1
 */

function md_logo() { ?>

	<?php get_template_part( 'content/logo' ); ?>

<?php }


/**
 * Displays the header menu.
 *
 * @since 4.1
 */

function md_menu() { ?>

	<?php get_template_part( 'content/menu' ); ?>

<?php }


/**
 * Displays titles for various types of archives.
 *
 * @since 4.0
 */

function md_content_box() { ?>

	<?php get_template_part( 'content/content-box' ); ?>

<?php }


/**
 * Displays content meta tags for Schema compatiblity.
 *
 * @since 4.4.2
 */

function md_content_schema() { ?>

	<?php get_template_part( 'content/content-schema' ); ?>

<?php }


/**
 * Displays titles for various types of archives.
 *
 * @since 4.0
 */

function md_archives_title() { ?>

	<?php get_template_part( 'content/archives-title' ); ?>

<?php }


/**
 * The Main Loop used on all posts, pages, and archives.
 *
 * @since 4.1
 */

function md_loop() { ?>

	<?php if ( md_has_teasers() && ( is_home() || is_search() || is_archive() ) ) : ?>

		<?php get_template_part( 'content/loop', 'teasers' ); ?>

	<?php else : ?>

		<?php get_template_part( 'content/loop' ); ?>

	<?php endif; ?>

<?php }


/**
 * Create pagination for use on home and archives pages.
 *
 * @since 4.0
 */

function md_pagination() { ?>

	<?php get_template_part( 'content/pagination' ); ?>

<?php }


/**
 * Displays full comments template from comments.php.
 *
 * @since 4.1
 */

function md_comments() { ?>

	<?php if ( md_has_comments() && is_singular() ) : ?>

		<div id="comments" class="comments inner content-item format-text-main">
			<div class="<?php echo md_content_block(); ?>">

				<?php comments_template(); ?>

			</div>
		</div>

	<?php endif; ?>

<?php }


/**
 * Creates previous/next post links at the end of a
 * single entry.
 *
 * @since 4.0
 */

function md_post_nav() { ?>

	<?php get_template_part( 'content/post-nav' ); ?>

<?php }


/**
 * Displays the headline of any post/page.
 *
 * @since 4.1
 */

function md_content_headline() { ?>

	<?php if ( md_has_headline() && ! md_has_headline_cover() ) : ?>
		<?php get_template_part( 'content/headline' ); ?>
	<?php endif; ?>

<?php }


/**
 * Displays post/page content text.
 *
 * @since 4.1
 */

function md_content_text() { ?>

	<?php get_template_part( 'content/text' ); ?>

<?php }


/**
 * Output post author, published date and number of comments.
 *
 * @since 4.0
 */

function md_byline() { ?>

	<?php if ( md_has_byline() ) : ?>
		<?php get_template_part( 'content/byline' ); ?>
	<?php endif; ?>

<?php }


/**
 * This function accounts for the output of an individual comment, and is referenced
 * in wp_list_comments(). see comments.php.
 *
 * @since 4.1
 */

function md_comment( $comment, $args, $depth ) { ?>

	<?php include( locate_template( 'content/comment.php' ) ); ?>

<?php }


/**
 * Theme author box. This box is used on top of author archive pages.
 *
 * It pulls information from the author's profile in the WordPress dashboard.
 * This way, all user's can show their bio after each post.
 *
 * @since 4.0
 */

function md_author_box() {
	get_template_part( 'content/author-box' );
}


/**
 * Add widgetized footer columns to footer.
 *
 * @since 4.5
 */

function md_footer_columns_template() {
	get_template_part( 'content/footer-columns' );
}


/**
 * Add widgetized footer copyright text to footer.
 *
 * @since 4.5
 */

function md_footer_copy() {
	get_template_part( 'content/footer-copy' );
}


/**
 * Outputs the standard WordPress password form with the
 * .form-attached class added to it.
 *
 * @since 4.0
 * @revised 4.3.5
 */

function md_password_form() {
    global $post;

    $label = 'pwbox-'.( empty( $post->ID ) ? rand() : $post->ID );
    $o =
		'<form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" method="post" class="form-attached">'.
			'<p>' . __( 'To view this protected post, enter the password below:', 'md' ) . '</p>'.
			'<input name="post_password" id="' . $label . '" class="form-input" type="password" placeholder="' . __( 'Enter the password&hellip;', 'md' ) . '" size="20" maxlength="20" />'.
			'<input type="submit" name="submit" class="form-submit" value="' . esc_attr__( 'Get Access', 'md' ) . '" />'.
		'</form>';

	return $o;
}

add_filter( 'the_password_form', 'md_password_form' );