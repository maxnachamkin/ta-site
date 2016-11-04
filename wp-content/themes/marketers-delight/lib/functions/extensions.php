<?php

/**
 * Removes inline CSS Subtitles plugin prints to frontend.
 * MD will handle this in style.css.
 *
 * @since 4.1.1
 */

if ( class_exists( 'Subtitles' ) &&  method_exists( 'Subtitles', 'subtitle_styling' ) )
    remove_action( 'wp_head', array( Subtitles::getInstance(), 'subtitle_styling' ) );