<?php

/**
 * Shorthand way to display a Table Lead field.
 *
 * @since 1.0
 */

function table_lead_field( $field ) {
	return page_lead_field( 'table_lead', $field );
}


/**
 * Check if page has the Table Lead. This function can be used
 * like any WordPress conditional (like is_page(), is_single(), etc.)
 * and is used in this plugin to load styles.
 *
 * @since 1.0
 */
function has_table_lead() {
	if ( table_lead_field( 'headline' ) || table_lead_field( 'desc' ) || has_table_lead_columns() )
		return true;
}



/**
 * Return the number of active columns. This function can
 * be used to detect if any columns are enabled by checking
 * for a value != to 0.
 *
 * @since 1.0
 */

function has_table_lead_columns() {
	if ( has_table_lead_col( 1 ) || has_table_lead_col( 2 ) || has_table_lead_col( 3 ) )
		return true;
}


/**
 * Counts how many active Funnel Lead columns there are.
 *
 * @since 1.0
 */

function table_lead_columns() {
	return count( array_filter( array(
		has_table_lead_col( 1 ),
		has_table_lead_col( 2 ),
		has_table_lead_col( 3 )
	) ) );
}


/**
 * Check individual column. Can be used to check for as
 * many columns as are created, using a digit for the
 * parameter entry.
 *
 * @since 1.0
 */

function has_table_lead_col( $c ) {
	$col_listing       = table_lead_field( "col{$c}_listing" );
	$col_listing_check = ! empty( $col_listing ) ? array_filter( (array) $col_listing[0] ) ? true : false : '';

	if (
		table_lead_field( "col{$c}_headline" )      ||
		table_lead_field( "col{$c}_subtitle" )      ||
		table_lead_field( "col{$c}_price" )         ||
		table_lead_field( "col{$c}_price_term" )    ||
		$col_listing_check                          ||
		table_lead_field( "col{$c}_button_action" ) ||
		table_lead_field( "col{$c}_button_text" )   ||
		table_lead_field( "col{$c}_footnotes" )
	)
		return true;
}