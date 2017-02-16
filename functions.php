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

/**
 * Multiple select customize control class.
 */
class Customize_Control_Multiple_Select extends WP_Customize_Control {

    /**
     * The type of customize control being rendered.
     */
    public $type = 'multiple-select';

    /**
     * Displays the multiple select on the customize screen.
     */
    public function render_content() {

    if ( empty( $this->choices ) )
        return;
    ?>
        <label>
            <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
            <select <?php $this->link(); ?> multiple="multiple" style="height: 100%;">
                <?php
                    foreach ( $this->choices as $value => $label ) {
                        $selected = ( in_array( $value, $this->value() ) ) ? selected( 1, 1, false ) : '';
                        echo '<option value="' . esc_attr( $value ) . '"' . $selected . '>' . $label . '</option>';
                    }
                ?>
            </select>
        </label>
    <?php }
}

if ( !function_exists( 'art_people_customize_register' ) ):
    function art_people_customize_register( $wp_customize )
    {
        $wp_customize->add_section(
            'rara_academic_art_people_settings',
            array(
                'title' => __( 'ArtPeople Settings', 'rara-academic' ),
                'description' => __( 'Custom Stuff', 'rara-academic' ),
                'priority' => 200,
                'capability' => 'edit_theme_options',
            )
        );

        $wp_customize->add_setting( 'art_people_post_like_pages' );

        $wp_customize->add_control(
            new Customize_Control_Multiple_Select(
                $wp_customize,
                'multiple_select_setting',
                array(
                    'settings' => 'art_people_post_like_pages',
                    'label'    => 'Page-like categories',
                    'section'  => 'rara_academic_art_people_settings',
                    'type'     => 'multiple-select',
                    'choices'  => array(
                        'google' => 'Google',
                        'bing' => 'Bing',
                        'duck' => 'DuckDuckGo'
                    )
                )
            )
        );
    }
endif;
add_action( 'customize_register', 'art_people_customize_register' );

// END ENQUEUE PARENT ACTION
