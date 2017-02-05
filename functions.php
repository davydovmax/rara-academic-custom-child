<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_parent_css' ) ):
    function chld_thm_cfg_parent_css() {
        wp_enqueue_style( 'chld_thm_cfg_parent', trailingslashit( get_template_directory_uri() ) . 'style.css', array( 'font-awesome','meanmenu','lightslider' ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'chld_thm_cfg_parent_css', 10 );
         
if ( !function_exists( 'child_theme_configurator_css' ) ):
    function child_theme_configurator_css() {
        wp_enqueue_style( 'chld_thm_cfg_separate', trailingslashit( get_stylesheet_directory_uri() ) . 'ctc-style.css', array( 'chld_thm_cfg_parent','rara-academic-style','rara-academic-responsive-style' ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'child_theme_configurator_css' );

if ( !function_exists( 'hide_post_the_posted_on_text' ) ):
    function hide_post_the_posted_on_text() {
        $categories_without_posted_on_text = array(
            'classes',
            'teachers'
        ); // Array of categories where we don't show posted-on-by text.

        $categories = get_the_category();

        foreach ( $categories as $index => $single_cat ) {

            if ( in_array( $single_cat->slug, $categories_without_posted_on_text ) ) {
                return true;
            }
        }

        return false;
    }
endif;

// END ENQUEUE PARENT ACTION
