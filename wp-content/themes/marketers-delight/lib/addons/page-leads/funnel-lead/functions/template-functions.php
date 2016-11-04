<?php

/**
 * Shorthand way to display a Funnel Lead field.
 *
 * @since 1.0
 */

function funnel_lead_field( $field ) {
	return page_lead_field( 'funnel_lead', $field );
}


/**
 * Check if page has the Funnel Lead. This function can be used
 * like any WordPress conditional (like is_page(), is_single(), etc.)
 * and is used in this plugin to load styles.
 *
 * @since 1.0
 */

function has_funnel_lead() {
	if ( funnel_lead_field( 'headline' ) || funnel_lead_field( 'desc' ) || has_funnel_lead_columns() )
		return true;
}


/**
 * Return the number of active columns. This function can
 * be used to detect if any columns are enabled by checking
 * for a value != to 0.
 *
 * @since 1.0
 */

function has_funnel_lead_columns() {
	if ( has_funnel_lead_col( 1 ) || has_funnel_lead_col( 2 ) || has_funnel_lead_col( 3 ) )
		return true;
}


/**
 * Counts how many active Funnel Lead columns there are.
 *
 * @since 1.0
 */

function funnel_lead_columns() {
	return count( array_filter( array(
		has_funnel_lead_col( 1 ),
		has_funnel_lead_col( 2 ),
		has_funnel_lead_col( 3 )
	) ) );
}


/**
 * Check individual column. Can be used to check for as
 * many columns as are created, using a digit for the
 * parameter entry.
 *
 * @since 1.0
 */

function has_funnel_lead_col( $c ) {
	if (
		funnel_lead_field( "col{$c}_image" )       ||
		funnel_lead_field( "col{$c}_headline" )    ||
		funnel_lead_field( "col{$c}_desc" )        ||
		funnel_lead_field( "col{$c}_button_link" ) ||
		funnel_lead_field( "col{$c}_button_text" )
	)
		return true;
}


/**
 * Outputs the Funnel Lead column image based on image ID.
 *
 * @since 1.0
 */

function funnel_lead_col_image( $url, $size, $link = null ) {
	if ( ! $url )
		return;

	$id  = md_url_image_id( $url );
	$img = wp_get_attachment_image_src( $id, $size );
?>

	<?php if ( $link ) : ?><a href="<?php echo esc_url( $link ); ?>"><?php endif; ?>

		<img src="<?php esc_attr_e( $url ); ?>" alt="<?php echo get_the_title(); ?>" width="<?php esc_attr_e( $img[1] ); ?>" height="<?php esc_attr_e( $img[2] ); ?>" class="<?php esc_attr_e( "col-image-$id" ); ?>" />

	<?php if ( $link ) : ?></a><?php endif; ?>

<?php }