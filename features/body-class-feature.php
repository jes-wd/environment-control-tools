<?php

namespace EnvironmentControlTools;

class Body_Class_Feature
{
    private $is_development;

    public function __construct($is_development)
    {
        $this->is_development = $is_development;
        $this->add_hooks();
    }

    private function add_hooks()
    {
        add_filter('admin_body_class', [$this, 'add_body_class']);
        add_filter('body_class', [$this, 'add_body_class']);

        add_action('admin_head', [$this, 'body_class_css']);
        add_action('wp_head', [$this, 'body_class_css']);
    }

    public function add_body_class($classes)
    {
        if ($this->is_development) {
            if (is_array($classes)) {
                $classes[] = 'environment-control-tools-admin-body-class';
            } else if (is_string($classes)) {
                $classes .= ' environment-control-tools-admin-body-class';
            }
        }
        return $classes;
    }

    public function body_class_css()
    {
        if (!$this->is_development) {
            return;
        }

        // Fetch the color from the WordPress database
        $admin_bar_color = get_option('jesect_admin_bar_color', '#2271b1'); // Fallback to #2271b1 if not set

        $custom_css = '
            body.environment-control-tools-admin-body-class #wpadminbar {
                background: ' . esc_attr($admin_bar_color) . ' !important;
            }
        ';
        // enqueue body-class.css
        wp_enqueue_style('environment-control-tools-body-class', JESECT_PLUGIN_URL . 'assets/css/body-class.css');
        wp_add_inline_style('environment-control-tools-body-class', $custom_css);
    }
}
