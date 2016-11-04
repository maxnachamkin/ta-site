<?php
	if ( is_singular() )
		return;

	global $wp_query;

	$big      = 999999999;
	$paginate = paginate_links( array(
		'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		'format'    => '?paged=%#%',
		'current'   => max( 1, get_query_var( 'paged' ) ),
		'prev_text' => '<i class="pagination-icon md-icon md-icon-angle-left"></i> ' . __( 'Previous', 'md' ),
		'next_text' => __( 'Next', 'md' ) . ' <i class="pagination-icon md-icon md-icon-angle-right"></i>',
		'total'     => $wp_query->max_num_pages
	) );
?>

<?php if ( $paginate ) : ?>

	<div class="pagination inner content-item block-single links-sec">
		<?php echo $paginate; ?>
	</div>

<?php endif; ?>
