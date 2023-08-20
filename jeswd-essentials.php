<?php
/*
Plugin Name:    JESWD Essentials
Plugin URI:     https://jeswebdevelopment.com
Description:    A collection of useful functions for JES-WD
Version:        0.0.1
Author:         JES-WD
Author URI:     https://jeswebdevelopment.com
License:        GPL2
WC tested up to: 6.5.1
*/

require plugin_dir_path(__FILE__) . 'includes/admin-page.php';

class JESWD_Essentials {
    private $is_development;
    private $production_site_url;

    public function __construct() {
        $this->production_site_url = base64_decode(get_option('jeswde_production_site_url'));
        $this->is_development = $this->check_development_mode();

        $this->add_hooks();
    }

    private function check_development_mode() {
        if (!$this->production_site_url) {
            return false;
        }

        // Remove the 'www.' prefix if present
        $current_host = str_replace('www.', '', $_SERVER['HTTP_HOST']);

        if ($current_host == $this->production_site_url) {
            return false;
        }

        if (strpos($current_host, $this->production_site_url) !== false) {
            return true;
        }

        return true;
    }

    private function add_hooks() {
        add_filter('admin_body_class', [$this, 'add_body_class']);
        add_filter('body_class', [$this, 'add_body_class']);

        add_action('admin_head', [$this, 'body_class_css']);
        add_action('wp_head', [$this, 'body_class_css']);

        add_action('login_head', [$this, 'add_favicon']);
        add_action('admin_head', [$this, 'add_favicon']);
        add_action('wp_head', [$this, 'add_favicon']);

        add_action('admin_init', [$this, 'activate_or_disable_plugins']);

        add_filter('plugin_action_links', [$this, 'modify_plugin_action_links'], 10, 4);
        add_action('init', [$this, 'update_search_engine_visibility']);
    }

    public function add_body_class($classes) {
        if ($this->is_development) {
            $classes .= ' jeswd-essentials-admin-body-class';
        }
        return $classes;
    }

    public function body_class_css() {
        if (!$this->is_development) {
            return;
        }

        // Fetch the color from the WordPress database
        $admin_bar_color = get_option('jeswde_admin_bar_color', '#2271b1'); // Fallback to #2271b1 if not set
?>
        <style>
            body.jeswd-essentials-admin-body-class #wpadminbar {
                background: <?php echo esc_attr($admin_bar_color); ?> !important;
            }
        </style>
<?php
    }

    public function add_favicon() {
        if (!$this->is_development) {
            return;
        }
        echo '<link rel="shortcut icon" href="' . plugin_dir_url(__FILE__) . 'jeswd-favicon.png" />';
    }

    public function activate_or_disable_plugins() {
        if (!$this->is_development) {
            return;
        }

        // error_log('jeswde: activate_or_disable_plugins');

        $plugins_to_activate = get_option('jeswde_plugins_to_activate', []);
        $plugins_to_deactivate = get_option('jeswde_plugins_to_deactivate', []);

        // error_log('jeswde: plugins_to_activate: ' . print_r($plugins_to_activate, true));
        // error_log('jeswde: plugins_to_deactivate: ' . print_r($plugins_to_deactivate, true));

        // Activate plugins
        foreach ($plugins_to_activate as $plugin) {
            if (!is_plugin_active($plugin)) {
                $result = activate_plugin($plugin);
                if (is_wp_error($result)) {
                    error_log($result->get_error_message());
                }
            }
        }

        // Deactivate plugins
        foreach ($plugins_to_deactivate as $plugin) {
            // error_log('jeswde: deactivating ' . $plugin);

            if (is_plugin_active($plugin)) {
                $result = deactivate_plugins($plugin);
                if (is_wp_error($result)) {
                    // error_log($result->get_error_message());
                }
            }
        }
    }

    public function modify_plugin_action_links($actions, $plugin_file, $plugin_data, $context) {
        // Retrieve the list of plugins to activate/deactivate
        $plugins_to_activate = get_option('jeswde_plugins_to_activate', []);
        $plugins_to_deactivate = get_option('jeswde_plugins_to_deactivate', []);

        // Check if current plugin is in one of the lists
        if (in_array($plugin_file, $plugins_to_activate) || in_array($plugin_file, $plugins_to_deactivate)) {
            // Unset activate/deactivate actions
            unset($actions['activate']);
            unset($actions['deactivate']);

            // get activated state
            $is_active = is_plugin_active($plugin_file);
            $active_state_text = $is_active ? 'Activated' : 'Deactivated';
            // Add link to JESWD Essentials settings page
            $settings_link = admin_url('options-general.php?page=jeswd-essentials');
            // Add custom text
            $actions['custom'] = "<span style='color: #555;'>{$active_state_text} by <a href='{$settings_link}'>JESWD Essentials</a></span>";
        }

        return $actions;
    }

    public function update_search_engine_visibility() {
        if ($this->is_development) {
            // If it's development mode, set the blog_public option to 0 (discourage search engines)
            if (get_option('blog_public') != 0) {
                update_option('blog_public', 0);
            }
        } else {
            // If it's production mode, set the blog_public option to 1 (allow search engines)
            if (get_option('blog_public') != 1) {
                update_option('blog_public', 1);
            }
        }
    }
}

new JESWD_Essentials();
