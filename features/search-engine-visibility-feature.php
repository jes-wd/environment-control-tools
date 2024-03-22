<?php

namespace EnvironmentControlTools;

class Search_Engine_Visibility_Feature {
    private $is_development;

    public function __construct($is_development) {
        $this->is_development = $is_development;
        $this->add_hooks();
    }

    private function add_hooks() {
        add_action('init', [$this, 'update_search_engine_visibility']);

        if ($this->is_development) {
            add_action('admin_init', [$this, 'disable_search_engine_option']);
            add_action('admin_init', [$this, 'inject_custom_message']);
        }
    }

    public function update_search_engine_visibility() {
        if ($this->is_development) {
            if (get_option('blog_public') != 0) {
                update_option('blog_public', 0);
            }
        } else {
            if (get_option('blog_public') != 1) {
                update_option('blog_public', 1);
            }
        }
    }

    public function disable_search_engine_option() {
        add_filter('pre_option_blog_public', function ($value) {
            return 0; // force the option value to be "0" for development mode
        });

        add_filter('option_blog_public', function ($value) {
            return 0; // force the option value to be "0" for development mode
        });
    }

    public function inject_custom_message() {
        $message = esc_html__("This option is being controlled by Environment Control Tools.", 'environment-control-tools');
        wp_enqueue_script('jquery');
        wp_add_inline_script('jquery', "document.addEventListener('DOMContentLoaded', function() {
            const el = document.querySelector('label[for=\"blog_public\"]');
            const checkbox = document.querySelector('#blog_public');
            if (el) {
                const span = document.createElement('span');
                span.textContent = '{$message}';
                span.style.color = 'red';
                span.style.display = 'block';
                span.style.marginTop = '10px';
                el.parentNode.appendChild(span);
            }
            if (checkbox) {
                checkbox.disabled = true;
            }
        });");
    }    
}
