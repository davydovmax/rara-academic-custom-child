<?php

/*
Widget Name: Category Post Grid
Description: Grid with category posts
Author: Author
Author URI: http://example.com
Widget URI: http://example.com/category-post-grid,
Video URI: http://example.com/category-post-grid
*/

class Category_Post_Grid_Widget extends SiteOrigin_Widget {

    function get_template_name($instance) {
        return 'category-post-grid-template';
    }

    function get_style_name($instance) {
        return '';
    }

    function __construct() {
        //Here you can do any preparation required before calling the parent constructor, such as including additional files or initializing variables.
        $option_categories = array();
        $category_lists = get_categories( array(
           'type'                     => 'post',
           'orderby'                  => 'name',
           'order'                    => 'ASC',
           'hide_empty'               => 1,
           'hierarchical'             => 1,
           'taxonomy'                 => 'category'
        ) );
        $option_categories[''] = __( 'Choose Category', 'rara-academic' );
        foreach( $category_lists as $category ){
            $option_categories[$category->term_id] = $category->name;
        }

        //Call the parent constructor with the required arguments.
        parent::__construct(
            // The unique id for your widget.
            'category-post-grid',

            // The name of the widget for display purposes.
            __('ArtPeople: Category Post Grid Widget', 'category-post-grid-text-domain'),

            // The $widget_options array, which is passed through to WP_Widget.
            // It has a couple of extras like the optional help URL, which should link to your sites help or support page.
            array(
                'description' => __('ArtPeople: Category Post Grid Widget (desc).', 'category-post-grid-text-domain'),
                'help'        => 'http://example.com/category-post-grid-docs',
            ),

            //The $control_options array, which is passed through to WP_Widget
            array(
            ),

            //The $form_options array, which describes the form fields used to configure SiteOrigin widgets. We'll explain these in more detail later.
            array(
                'target_category' => array(
                    'type' => 'select',
                    'label' => __( 'Select Category', 'widget-form-fields-text-domain' ),
                    'default' => '',
                    'options' => $option_categories,
                ),
                'section_title' => array(
                    'type' => 'text',
                    'label' => __( 'Section Title Text', 'widget-form-fields-text-domain' ),
                    'default' => '',
                ),
                'section_content' => array(
                    'type' => 'text',
                    'label' => __( 'Section Description', 'widget-form-fields-text-domain' ),
                    'default' => '',
                ),
                'number_of_rows' => array(
                    'type' => 'slider',
                    'label' => __( 'Number of rows', 'widget-form-fields-text-domain' ),
                    'default' => 1,
                    'min' => 1,
                    'max' => 6,
                    'integer' => true
                ),
            ),

            //The $base_folder path string.
            rara_academic_child_theme_folder()
        );
    }
}

siteorigin_widget_register('category-post-grid', __FILE__, 'Category_Post_Grid_Widget');
