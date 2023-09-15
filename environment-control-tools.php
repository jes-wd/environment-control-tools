<?php
/*
Plugin Name:    Environment Control Tools
Plugin URI:     https://jesweb.dev
Description: Easily manage and visually differentiate between production and development environments in WordPress. This toolkit offers features such as controlling plugin behavior, adjusting search engine visibility, and visual cues like favicon changes and admin bar color modifications. Simplify your workflow and ensure a clear distinction between your environments.
Version:        1.0.0
Author:         jesweb.dev
Author URI:     https://jesweb.dev
License:        GPL2
*/

namespace EnvironmentControlTools;

define('ECT_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('ECT_PLUGIN_URL', plugin_dir_url(__FILE__));
define('ECT_PLUGIN_SLUG', 'environment-control-tools');
define('ECT_PLUGIN_BASENAME', plugin_basename(__FILE__));

require ECT_PLUGIN_DIR . 'includes/admin-page/admin-page.php';
require ECT_PLUGIN_DIR . 'includes/utilities.php';

// Features
require ECT_PLUGIN_DIR . 'features/body-class-feature.php';
require ECT_PLUGIN_DIR . 'features/favicon-feature.php';
require ECT_PLUGIN_DIR . 'features/plugin-management-feature.php';
require ECT_PLUGIN_DIR . 'features/search-engine-visibility-feature.php';

class EnvironmentControlTools {
    private $is_development;

    public function __construct() {
        $this->is_development = Utilities::check_development_mode();

        $this->initialize_features();

        new Admin_Page($this->is_development);

        add_filter('plugin_action_links_' . ECT_PLUGIN_BASENAME, array($this, 'plugin_settings_link'));
    }

    private function initialize_features() {
        new Body_Class_Feature($this->is_development);
        new Favicon_Feature($this->is_development);
        new Plugin_Management_Feature($this->is_development);
        new Search_Engine_Visibility_Feature($this->is_development);
    }

    public function plugin_settings_link($links) {
        $settings_link = '<a href="options-general.php?page=' . ECT_PLUGIN_SLUG . '">Settings</a>';
        array_unshift($links, $settings_link);

        return $links;
    }
}

new EnvironmentControlTools();
