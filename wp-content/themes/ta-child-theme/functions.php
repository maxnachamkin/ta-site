<?php

//Hero lead
function ta_hero_lead() { ?>

  <?php if (! is_front_page())
    return;
  ?>

  <?php get_template_part('templates/header', 'lead'); ?>

<?php }

add_action('md_hook_after_header', 'ta_hero_lead');


//Checklist Lead
function ta_checklist_lead() { ?>

  <?php if (! is_front_page())
    return;
  ?>

  <?php get_template_part('templates/checklist', 'lead'); ?>

<?php }

add_action('md_hook_after_header', 'ta_checklist_lead');

//Body Lead
function ta_body_lead() { ?>

  <?php if (! is_front_page())
    return;
  ?>

  <?php get_template_part('templates/body', 'lead'); ?>

<?php }

add_action('md_hook_after_header', 'ta_body_lead');


// ensures [shortcodes] work in text widgets
add_filter( 'widget_text', 'do_shortcode' );




/**
 * Loads main MD stylesheet + your custom stylesheet.
 *
 * @since 1.0
 */

function ta_child_theme_enqueue_style() {
    wp_enqueue_style( 'marketers-delight', MD_URL . 'style.css' );
    wp_enqueue_style( 'ta-child-theme', get_stylesheet_uri() );
    wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css?family=Titillium+Web:400,600,700,900', false );
}

add_action( 'wp_enqueue_scripts', 'ta_child_theme_enqueue_style' );




/**
 * Add MD meta options to any Custom Post Type.
 * Uncomment add_filter() and set proper IDs for your post type(s).
 * Read more: https://kolakube.com/guides/metaboxes-custom-post-types-taxonomies/
 *
 * @since 1.1
 */

function md_add_post_type_meta() {
	return array( 'download', 'product' );
}

//add_filter( 'md_post_type_meta', 'md_add_post_type_meta' );


/**
 * Add MD meta options to any Custom Taxonomy.
 * Uncomment add_filter() and set proper IDs for your taxonomies.
 * Read more: https://kolakube.com/guides/metaboxes-custom-post-types-taxonomies/
 *
 * @since 1.1
 */

function md_add_taxonomy_meta() {
	return array( 'download_category', 'product_cat' );
}

//add_filter( 'md_taxonomy_meta', 'md_add_taxonomy_meta' );
