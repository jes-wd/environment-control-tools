<?php

namespace JESWD_Essentials;

class Search_Engine_Visibility_Feature {
    private $is_development;

    public function __construct($is_development) {
        $this->is_development = $is_development;
        $this->add_hooks();
    }

    private function add_hooks() {
        add_action('init', [$this, 'update_search_engine_visibility']);
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
}
