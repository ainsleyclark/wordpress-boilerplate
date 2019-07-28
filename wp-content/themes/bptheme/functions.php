<?php

date_default_timezone_set ( 'Europe/London' );
define('DISABLE_WP_CRON', true);

/***********************************************************************************************/
/* 	Define Constants */
/***********************************************************************************************/
define('THEMEROOT', get_stylesheet_directory_uri());
define('IMAGES', THEMEROOT.'/images');

/***********************************************************************************************/
/* 	Define Content width -- Required */
/***********************************************************************************************/

if ( ! isset( $content_width ) ) {
    $content_width = 992;
}

/***********************************************************************************************/
/* 	Theme text domain */
/***********************************************************************************************/

load_theme_textdomain( 'boilerplate', THEMEROOT .'/languages' );

/***********************************************************************************************/
/* 	Load Styles */
/***********************************************************************************************/

function theme_styles_load() {

    // Register:
    wp_register_style( 'master-style', THEMEROOT . '/public/css/app.css');
    // Enqueue:
    wp_enqueue_style( 'master-style' );

}
add_action( 'wp_enqueue_scripts', 'theme_styles_load' );

/***********************************************************************************************/
/* 	Load Scripts */
/***********************************************************************************************/

//Global
function theme_scripts_load() {

    //Deregister the included library
    wp_deregister_script( 'jquery' );

    //Register:
    wp_register_script( 'custom-script', THEMEROOT . '/public/js/app.js', array(), false, true);
    // Localize:
    wp_localize_script( 'custom-script', 'dataxObj', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'homeurl' => home_url(),
    ));
    // Enqueue:
    wp_enqueue_script( 'custom-script' );

}
add_action( 'wp_enqueue_scripts', 'theme_scripts_load' );

/***********************************************************************************************/
/* Includes */
/***********************************************************************************************/

/**
 * Disable Comments/Emoji's
 * Disables all comments & emoji's on wordpress.
 */

if ( ! file_exists( get_template_directory() . '/includes/disable-comments-emojis.php' ) ) {
	return new WP_Error( 'disable-comments-emojis.php', __( 'It appears the disable-comments-emojis.php file may be missing.', 'disable-comments-emojis' ) );
} else {
    require_once get_template_directory() . '/includes/disable-comments-emojis.php';
}

/**
 * Minify HTML
 * Minifies all HTML.
 */

if ( ! file_exists( get_template_directory() . '/includes/class-minify-html.php' ) ) {
	return new WP_Error( 'class-minify-html', __( 'It appears the class-minify-html.php file may be missing.', 'class-html-minify' ) );
} else {
    require_once get_template_directory() . '/includes/class-minify-html.php';
}

/**
 * Admin Ajax
 * Creates contact form submissions
 */

if ( ! file_exists( get_template_directory() . '/includes/admin-ajax.php' ) ) {
	return new WP_Error( 'admin-ajax', __( 'It appears the admin-ajax file may be missing.', 'admin-ajax' ) );
} else {
    require_once get_template_directory() . '/includes/admin-ajax.php';
}

/***********************************************************************************************/
/* 	Theme support */
/***********************************************************************************************/

add_theme_support( 'post-thumbnails' );

/***********************************************************************************************/
/* Add Menus */
/***********************************************************************************************/
function register_theme_menus(){
    register_nav_menus(
        array(
            'main-menu' => __('Main Menu', 'vitae'),
            'footer-menu' => __('Footer Menu', 'vitae'),
            'footer-menu2' => __('Footer Menu 2', 'vitae'),
        )
    );
}
add_action('init', 'register_theme_menus');

/***********************************************************************************************/
/* Add ACF admin pages */
/***********************************************************************************************/

if( function_exists('acf_add_options_page') ) {

    acf_add_options_page(array(
        'page_title' 	=> 'Website Options',
        'menu_title'	=> 'Website Options',
        'menu_slug' 	=> 'website-options',
        'capability'	=> 'edit_posts',
        'redirect'		=> false,
        'icon_url' => 'dashicons-admin-generic',
        'position' => 34
    ));
}


