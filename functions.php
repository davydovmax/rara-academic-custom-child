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
        $categories = get_the_category();

        $page_like_categories = get_theme_mod( 'art_people_page_like_categories' );

        if(!$page_like_categories) {
            return false;
        }

        foreach ( $categories as $index => $single_cat ) {
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
                    'label' => __( 'Page-like categories', 'todo_fix_translation_domain' ),
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

function rara_academic_child_theme_folder() {
    return get_stylesheet_directory();
}

function add_art_people_widgets($folders){
    $folders[] = get_stylesheet_directory() . 'widgets';
    return $folders;
}
add_filter('siteorigin_widgets_widget_folders', 'add_art_people_widgets');

require get_stylesheet_directory() . '/widgets/category-post-grid/category-post-grid.php';

function art_people_activate_bundled_widgets(){
    if( !get_theme_mod('bundled_widgets_activated') ) {
        SiteOrigin_Widgets_Bundle::single()->activate_widget( 'category-post-grid' );
        set_theme_mod( 'bundled_widgets_activated', true );
    }
}
add_filter('admin_init', 'art_people_activate_bundled_widgets');

add_action( 'init', 'my_add_excerpts_to_pages' );
function my_add_excerpts_to_pages() {
     add_post_type_support( 'page', 'excerpt' );
}

function ap_get_sh_post_types() {
    $sh_post_types = get_post_types( '', 'names' );
    unset( $sh_post_types['attachment'], $sh_post_types['revision'], $sh_post_types['nav_menu_item'] );
    return $sh_post_types;
}
/*
Based on plugin 'Hide Featured Image' (http://shahpranav.com/2015/05/hide-featured-image-on-single-post/)
By shahpranaf http://shahpranav.com/
*/
function sh_post_types_custom_box() {
    $sh_post_types = ap_get_sh_post_types();

    foreach ($sh_post_types as $post_type) {
        add_meta_box( 'ap_hide_featured_single', __( 'Hide Featured Image?', 'HideImage' ), 'sh_featured_box', $post_type, 'side', 'default' );
    }
}
/*
Based on plugin 'Hide Featured Image' (http://shahpranav.com/2015/05/hide-featured-image-on-single-post/)
By shahpranaf http://shahpranav.com/
*/
function sh_featured_box($post){
    wp_nonce_field( plugin_basename( __FILE__ ), $post->post_type . '_noncename' );
    $hide_featured = get_post_meta( $post->ID, '_ap_hide_featured_single', true ) ? 1 : 0; ?>
    <input type="radio" name="_ap_hide_featured_single" value="1" <?php checked( $hide_featured, 1 ); ?>><?php _e( 'Yes', 'HideImage' ); ?>&nbsp;&nbsp;
    <input type="radio" name="_ap_hide_featured_single" value="0" <?php checked( $hide_featured, 0 ); ?>><?php _e( 'No', 'HideImage' ); ?><?php
}
add_action( 'add_meta_boxes', 'sh_post_types_custom_box' ); // WP 3.0+

/*
Based on plugin 'Hide Featured Image' (http://shahpranav.com/2015/05/hide-featured-image-on-single-post/)
By shahpranaf http://shahpranav.com/
*/
function sh_post_types_save_postdata( $post_id ) {
    $sh_post_types = ap_get_sh_post_types();

    // verify if this is an auto save routine.
    // If it is our form has not been submitted, so we don't want to do anything
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
      return;

    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if ( !wp_verify_nonce( @$_POST[$_POST['post_type'] . '_noncename'], plugin_basename( __FILE__ ) ) )
      return;

    // user can edit
    if ( !current_user_can( 'edit_page', $post_id ) ) {
      return;
    }

    // OK,nonce has been verified and now we can save the data
    if( in_array($_POST['post_type'], $sh_post_types) ) {
      $hide_featured = (
        isset( $_POST['_ap_hide_featured_single'] )
        && $_POST['_ap_hide_featured_single'] == 1 )
      ? '1'
      : $_POST['_ap_hide_featured_single'];
      update_post_meta( $post_id, '_ap_hide_featured_single', $hide_featured );
    }
}
add_action( 'save_post', 'sh_post_types_save_postdata' ); /* Do something with the data entered */

function set_feature_image_visibility( $post ) {
    // abort if it's not a single post
    if( get_queried_object_id() != $post->ID ) {
        return;
    }

    if ( !(is_single( $post->ID ) || is_page( $post->ID ) ) ) {
        return;
    }

    $hide = get_post_meta( get_the_ID(), '_ap_hide_featured_single', true );/* Hide single post */;

    // hide the featured image if it was set so
    if ( $hide ) {
        add_filter( 'get_post_metadata', 'ap_hide_featured_image_filter', 10, 4 );
    }
}
function ap_hide_featured_image_filter( $value, $post_id, $meta_key, $single ) {
    if ( $single && '_thumbnail_id' == $meta_key && $post_id == get_queried_object_id() ) {
        return false;
    }
}
add_action( 'the_post', 'set_feature_image_visibility' );

function art_people_exclude_page_like_category( $query ) {
    if ( $query->is_home() && $query->is_main_query() ) {

        $page_like_categories = get_theme_mod( 'art_people_page_like_categories' );

        if( !$page_like_categories ) {
            return false;
        }

        $query->set( 'category__not_in', $page_like_categories );
    }
}
add_action( 'pre_get_posts', 'art_people_exclude_page_like_category' );
