<?php
/*
Widget Name: U of U Theme Starter Widget
Description: This is a widget template for creating custom widgets within a child theme.
Author: Brian Thurber
Author URI: https://umc.utah.edu
*/

class Theme_Starter_Widget extends SiteOrigin_Widget {
  function __construct() {
      parent::__construct(
            'theme-starter-widget', // Widget ID
            __('Theme Starter Widget', 'theme-starter-widget-text-domain'), // Widget Name
            array(
                'description' => __('Another SiteOrigin widget.', 'theme-starter-widget-text-domain'),
                //'groups' => array('umc-widgets') // Explicitly set the group
            ),
            array(),
            //FIELDS (FORM OPTIONS)
            array(
                // Title
                'uu_theme_starter_widget_text' => array(
                    'type' => 'text',
                    'label' => __('Title', 'theme-starter-widget-text-domain'),
                    'default' => 'default',
                    'description' => __('Enter the title.', 'theme-starter-widget-text-domain'),
                ),

            ),
            get_stylesheet_directory() . '/siteorigin-widgets/theme-starter-widget'
      );

  }

  function get_template_name($instance) {
      return 'uu-theme-starter-widget-template'; //must match the file name in tpl folder
  }

  function get_template_dir($instance) {
      return 'tpl';
  }

  function widget($args, $instance) {
        echo $args['before_widget']; // This ensures SiteOrigin applies its wrapper div
        $template_path = get_stylesheet_directory() . '/siteorigin-widgets/theme-starter-widget/tpl/uu-theme-starter-widget-template.php';
      
        if (file_exists($template_path)) {
            include $template_path;
        } else {
            echo '<div style="color: red;">Error: Template file not found at ' . esc_html($template_path) . '</div>';
        }
        echo $args['after_widget']; // Close wrapper
  }
}