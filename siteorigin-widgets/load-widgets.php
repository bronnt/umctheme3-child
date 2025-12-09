<?php
// Define an array of widgets to be loaded
$custom_widgets = array(
    'theme-starter-widget' => 'Theme_Starter_Widget',
    'brians-widget' => 'Brians_Widget',
    // Add additional widgets here as needed
);

// Load custom SiteOrigin widgets
function load_custom_siteorigin_widgets() {
    global $custom_widgets;

    static $executed = false;
    if ($executed) return;
    $executed = true;

    if (class_exists('SiteOrigin_Widget')) {
        $widget_dir = get_stylesheet_directory() . '/siteorigin-widgets/';

        foreach ($custom_widgets as $folder => $class_name) {
            $widget_file = $widget_dir . $folder . '/' . 'uu-' . $folder . '.php';

            if (file_exists($widget_file)) {
                require_once $widget_file;

                if (class_exists($class_name)) {
                    register_widget($class_name);
                } else {
                }
            } else {
            }
        }
    }
}

if (!did_action('widgets_init')) {
    add_action('widgets_init', 'load_custom_siteorigin_widgets');
}

// Create a custom tab that displays only these specific widgets by name
function add_umc_widget_tabs($tabs) {
    $theme_name = wp_get_theme()->get('Name');

    $tabs[] = array(
        'title' => sprintf(__('%s Widgets', 'umc-widgets'), $theme_name),
        'filter' => array(
            'groups' => array('umc-widgets')
        ),
    );
    return $tabs;
}
add_filter('siteorigin_panels_widget_dialog_tabs', 'add_umc_widget_tabs', 20);

// Add the custom widgets to the 'umc-widgets' group
function add_custom_widgets_to_uu_group($widgets) {
    global $custom_widgets;

    foreach ($custom_widgets as $folder => $class_name) {
        if (isset($widgets[$folder])) {
            $widgets[$folder]['groups'][] = 'umc-widgets';
        }
    }

    return $widgets;
}
add_filter('siteorigin_panels_widgets', 'add_custom_widgets_to_uu_group', 12);

// Clear SiteOrigin's internal widget cache
add_action('init', function() {
    if (function_exists('siteorigin_widget_set_active_widgets_cache')) {
        siteorigin_widget_set_active_widgets_cache(null);
    }
});

// DISABLE LESS PROCESSING DYNAMICALLY
if (class_exists('SiteOrigin_Widget')) {
    class Custom_SiteOrigin_Widget extends SiteOrigin_Widget {
        public function __construct($id_base, $name, $widget_options = array(), $control_options = array(), $form_options = array(), $base_folder = '') {
            parent::__construct($id_base, $name, $widget_options, $control_options, $form_options, $base_folder);
        }

        public function get_instance_css($instance) {
            if (is_admin()) {
                error_log('[DEBUG] Allowing LESS processing in admin for widget: ' . $this->id_base);
                return parent::get_instance_css($instance);
            }

            return '';
        }
    }

    function replace_siteorigin_widgets_with_custom_less_handling($widget_map) {
        foreach ($widget_map as $original_class => $custom_class) {
            if (class_exists($original_class)) {
                unregister_widget($original_class);

                if (!class_exists($custom_class)) {
                    eval("
                        class $custom_class extends $original_class {
                            public function get_instance_css(\$instance) {
                                if (is_admin()) {
                                    return parent::get_instance_css(\$instance);
                                }
                                return '';
                            }
                        }
                    ");
                }

                register_widget($custom_class);
            }
        }
    }

    add_action('widgets_init', function() {
        global $custom_widgets;
        $widgets_to_override = array();

        foreach ($custom_widgets as $folder => $class_name) {
            $widgets_to_override[$class_name] = 'Custom_' . $class_name;
        }

        replace_siteorigin_widgets_with_custom_less_handling($widgets_to_override);
    }, 11);
}