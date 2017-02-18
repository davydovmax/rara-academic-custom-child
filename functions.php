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

// END ENQUEUE PARENT ACTION

if ( ! function_exists('write_log')) {
   function write_log ( $log )  {
      if ( is_array( $log ) || is_object( $log ) ) {
         error_log( print_r( $log, true ) );
      } else {
         error_log( $log );
      }
   }
}

if ( !function_exists( 'post_should_behave_like_page' ) ):
    function post_should_behave_like_page() {
        // $categories_without_posted_on_text = array(
        //     'classes',
        //     'teachers'
        // ); // Array of categories where we don't show posted-on-by text.

        $categories = get_the_category();

        $page_like_categories = get_theme_mod( 'art_people_page_like_categories' );

        write_log($page_like_categories);

        if(!$page_like_categories) {
            return false;
        }

        foreach ( $categories as $index => $single_cat ) {

            write_log($single_cat->term_id);

            if ( in_array( $single_cat->term_id, $page_like_categories ) ) {
                return true;
            }
        }

        return false;
    }
endif;

if ( !function_exists( 'art_people_customize_register' ) ):
    function art_people_customize_register( $wp_customize )
    {
        require get_stylesheet_directory() . '/inc/multi-select.php';

        $wp_customize->add_section(
            'rara_academic_art_people_settings',
            array(
                'title' => __( 'ArtPeople Settings', 'rara-academic' ),
                'description' => __( 'Custom Stuff', 'rara-academic' ),
                'priority' => 200,
                'capability' => 'edit_theme_options',
            )
        );

        $wp_customize->add_setting( 'art_people_page_like_categories' );

        $args = array(
           'type'                     => 'post',
           'orderby'                  => 'name',
           'order'                    => 'ASC',
           'hide_empty'               => 1,
           'hierarchical'             => 1,
           'taxonomy'                 => 'category'
        );
        $option_categories = array();
        $category_lists = get_categories( $args );

        foreach( $category_lists as $category ){
            $option_categories[$category->term_id] = $category->name;
        }

        $wp_customize->add_control(
            new Customize_Control_Multiple_Select(
                $wp_customize,
                'multiple_select_setting',
                array(
                    'settings' => 'art_people_page_like_categories',
                    'label'    => 'Page-like categories',
                    'section'  => 'rara_academic_art_people_settings',
                    'type'     => 'multiple-select',
                    'choices'  => $option_categories,
                )
            )
        );
    }
endif;
add_action( 'customize_register', 'art_people_customize_register' );

if ( ! function_exists('rara_academic_entry_footer')) {
    function rara_academic_entry_footer() {
    }
}
