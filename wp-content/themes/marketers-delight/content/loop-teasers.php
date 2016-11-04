<?php if ( have_posts() ) : ?>

	<div class="blog-teasers inner mb-single">

		<div class="columns-<?php echo md_has_sidebar() ? '2' : '3'; ?> columns-single columns-flex">

			<?php while ( have_posts() ) : the_post(); ?>

				<article id="content-<?php the_ID(); ?>" <?php post_class( array( 'col', 'mb-single' ) ); ?> <?php md_article_schema(); ?>>
					<div class="blog-teaser">

						<?php md_featured_image( 'above_headline', 'md-banner' ); ?>

						<div class="box-style block-single">

							<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

							<?php md_byline(); ?>

							<div class="format-text-sec">
								<?php the_excerpt(); ?>
							</div>

						</div>

					</div>
				</article>

			<?php endwhile; ?>

		</div>

	</div>

<?php else : ?>

	<?php get_template_part( 'content/content-item-404' ); ?>

<?php endif; ?>