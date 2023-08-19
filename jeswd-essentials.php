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
            return true;
        }
        
        if ($_SERVER['HTTP_HOST'] == $this->production_site_url) {
            return false;
        }
        
        if (strpos($_SERVER['HTTP_HOST'], $this->production_site_url) !== false) {
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
?>
        <style>
            body.jeswd-essentials-admin-body-class #wpadminbar {
                background: #2271b1 !important;
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
        
        $disable_emails = 'disable-emails/disable-emails.php';
        if (!is_plugin_active($disable_emails)) {
            $result = activate_plugin($disable_emails);
            if (is_wp_error($result)) {
                error_log($result->get_error_message());
            }
        }

        $post_smtp = 'post-smtp/postman-smtp.php';
        if (is_plugin_active($post_smtp)) {
            $result = deactivate_plugins($post_smtp);
            if (is_wp_error($result)) {
                error_log($result->get_error_message());
            }
        }
    }
}

new JESWD_Essentials();

