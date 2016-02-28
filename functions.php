<?php

/**
 * Enqueue scripts and styles.
 *
 * @since Folio 0.1
 */
function folioScripts() {
    // Add Vendor CSS, used By Foundation.
    wp_enqueue_style( 'vendor', get_template_directory_uri() . '/css/vendor.css');


    // Load our main stylesheet.
    wp_enqueue_style( 'folio-style', get_template_directory_uri() . '/css/front.css' );
}
add_action( 'wp_enqueue_scripts', 'folioScripts' );