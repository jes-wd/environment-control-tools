<?php

namespace EnvironmentControlTools;

class Plugin_Management_Feature {
    private $is_development;

    public function __construct($is_development) {
        $this->is_development = $is_development;
        $this->add_hooks();
    }

    private function add_hooks() {
        add_action('admin_init', [$this, 'activate_or_disable_plugins']);
        add_filter('plugin_action_links', [$this, 'modify_plugin_action_links'], 10, 4);
    }

    public function activate_or_disable_plugins() {
        // Decide which option keys to use
        $option_suffix = $this->is_development ? '_dev' : '_production';
        $plugins_to_activate_key = 'jesect_plugins_to_activate' . $option_suffix;
        $plugins_to_deactivate_key = 'jesect_plugins_to_deactivate' . $option_suffix;
    
        // Fetch the options
        $plugins_to_activate = get_option($plugins_to_activate_key, []);
        $plugins_to_deactivate = get_option($plugins_to_deactivate_key, []);
    
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
            if (is_plugin_active($plugin)) {
                $result = deactivate_plugins($plugin);
                if (is_wp_error($result)) {
                    error_log($result->get_error_message());
                }
            }
        }
    }    

    public function modify_plugin_action_links($actions, $plugin_file, $plugin_data, $context) {
        // Decide which option keys to use based on the development mode
        $option_suffix = $this->is_development ? '_dev' : '_production';
        $plugins_to_activate_key = 'jesect_plugins_to_activate' . $option_suffix;
        $plugins_to_deactivate_key = 'jesect_plugins_to_deactivate' . $option_suffix;
    
        // Fetch the options
        $plugins_to_activate = get_option($plugins_to_activate_key, []);
        $plugins_to_deactivate = get_option($plugins_to_deactivate_key, []);
    
        if (in_array($plugin_file, $plugins_to_activate) || in_array($plugin_file, $plugins_to_deactivate)) {
            unset($actions['activate']);
            unset($actions['deactivate']);
    
            $is_active = is_plugin_active($plugin_file);
            $active_state_text = $is_active ? 'Activated' : 'Deactivated';
            $settings_link = admin_url('options-general.php?page=environment-control-tools');
            $actions['custom'] = "<span style='color: #555;'>{$active_state_text} by <a href='{$settings_link}'>Environment Control Tools</a></span>";
        }
    
        return $actions;
    }    
}
