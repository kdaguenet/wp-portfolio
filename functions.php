<?php
/**
 * Enqueue scripts and styles.
 *
 * @since Folio 0.1
 */
function folioScripts()
{
    // Add Vendor CSS, used By Foundation.
    wp_enqueue_style('vendor', get_template_directory_uri() . '/css/vendor.css');
    // Load our main stylesheet.
    wp_enqueue_style('folio-style', get_template_directory_uri() . '/css/front.css');
}

add_action('wp_enqueue_scripts', 'folioScripts');

/**
 * Add Administration menu
 * @since Folio 0.1
 */
require get_template_directory() . '/admin/admin-menu.php';

/**********
 * Activation des sidesbar du site
 **********/
function myfolio_widgets_init() {
    require get_template_directory() . '/inc/skill-widget.php';
    register_widget( 'myFolio_Skill_Widget' );

    register_sidebar( array(
        'name'          => __( 'Content Sidebar', 'myfolio' ),
        'id'            => 'sidebar-1',
        'description'   => __( 'Sidebar for the main page', 'myfolio' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="section-title">',
        'after_title'   => '</h2>',
    ) );
}
add_action( 'widgets_init', 'myfolio_widgets_init' );