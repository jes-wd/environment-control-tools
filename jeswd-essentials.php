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

namespace JESWD_Essentials;

define('JESWD_ESSENTIALS_PLUGIN_DIR', plugin_dir_path(__FILE__));

require JESWD_ESSENTIALS_PLUGIN_DIR . 'includes/admin-page/admin-page.php';
require JESWD_ESSENTIALS_PLUGIN_DIR . 'includes/utilities.php';

// Features
require JESWD_ESSENTIALS_PLUGIN_DIR . 'features/body-class-feature.php';
require JESWD_ESSENTIALS_PLUGIN_DIR . 'features/favicon-feature.php';
require JESWD_ESSENTIALS_PLUGIN_DIR . 'features/plugin-management-feature.php';
require JESWD_ESSENTIALS_PLUGIN_DIR . 'features/search-engine-visibility-feature.php';

class JESWD_Essentials {
    private $is_development;

    public function __construct() {
        $this->is_development = Utilities::check_development_mode();

        $this->initialize_features();

        new Admin_Page($this->is_development);
    }

    private function initialize_features() {
        new Body_Class_Feature($this->is_development);
        new Favicon_Feature($this->is_development);
        new Plugin_Management_Feature($this->is_development);
        new Search_Engine_Visibility_Feature($this->is_development);
    }
}

new JESWD_Essentials();
