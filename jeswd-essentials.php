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

// set var to check if the site is in development mode
function jeswde_is_development_mode() {
    $jeswde_is_development = false;
    $production_site_url = base64_decode(get_option('jeswde_production_site_url'));

    if ($production_site_url) {
        $jeswde_is_development = (strpos($_SERVER['HTTP_HOST'], $production_site_url) === false);
    }

    return $jeswde_is_development;
}

$jeswde_is_development = jeswde_is_development_mode();

// if the current site domain name does not match the production site url, add an orange border to the <body> tag of wp-admin
function jeswde_body_class($classes) {
    global $jeswde_is_development;

    if ($jeswde_is_development) {
        $classes .= ' jeswd-essentials-admin-body-class';
    }

    return $classes;
}

// add the body class
add_filter('admin_body_class', 'jeswde_body_class');
// also add to the frontend
add_filter('body_class', 'jeswde_body_class');

// add the css
function jeswde_body_class_css() {
?>
    <style>
        body.jeswd-essentials-admin-body-class #wpadminbar {
            background: #2271b1 !important;
        }
    </style>
<?php
}

// add the css
add_action('admin_head', 'jeswde_body_class_css');
// also add to the frontend
add_action('wp_head', 'jeswde_body_class_css');

// change the favicon to the JES-WD favicon
function jeswde_favicon() {
    global $jeswde_is_development;

    if ($jeswde_is_development) {
        // change the wp setting for the favicon
        // update_option( 'site_icon', 0 );
        echo '<link rel="shortcut icon" href="' . plugin_dir_url(__FILE__) . 'jeswd-favicon.png" />';
    }
}

// add the favicon
add_action('login_head', 'jeswde_favicon');
add_action('admin_head', 'jeswde_favicon');
add_action('wp_head', 'jeswde_favicon');

function jeswde_handle_plugins() {
    $managed_plugins = [
        [
            'file' => 'disable-emails/disable-emails.php',
            'active_state' => 'development'
        ],
        [
            'file' => 'post-smtp/postman-smtp.php',
            'active_state' => 'production'
        ]
    ];

    foreach ($managed_plugins as $plugin) {
        handle_plugin_active_state($plugin);
    }
}

// if is development mode, add admin notices to install plugins that need to be active on development
function jeswde_admin_notices() {
    global $jeswde_is_development;

    if ($jeswde_is_development) {
        $managed_plugins = [
            [
                'file' => 'disable-emails/disable-emails.php',
                'active_state' => 'development'
            ],
            [
                'file' => 'post-smtp/postman-smtp.php',
                'active_state' => 'production'
            ]
        ];

        foreach ($managed_plugins as $plugin) {
            if ($plugin['active_state'] !== 'development') {
                continue;
            }

            $plugin_file_path = WP_PLUGIN_DIR . '/' . $plugin['file'];
            $is_plugin_installed = file_exists($plugin_file_path);

            if (!$is_plugin_installed) {
                $plugin_slug = explode('/', $plugin['file'])[0];
                $plugin_install_url = admin_url('plugin-install.php?s=' . $plugin_slug . '&tab=search&type=term');
                $plugin_install_link = '<a href="' . $plugin_install_url . '" target="_blank">' . $plugin_slug . '</a>';
                $plugin_install_message = 'The plugin ' . $plugin_install_link . ' is not installed. This should be installed immediately on the development site.';

                echo '<div class="notice notice-warning is-dismissible"><p>' . $plugin_install_message . '</p></div>';
            }
        }
    }
}

// add the admin notices
add_action('admin_notices', 'jeswde_admin_notices');


function handle_plugin_active_state(array $plugin) {
    $is_development = jeswde_is_development_mode();
    $plugin_file_path = WP_PLUGIN_DIR . '/' . $plugin['file'];
    $should_activate = ($plugin['active_state'] === 'development') && !is_plugin_active($plugin_file_path) && $is_development;
    $should_deactivate = ($plugin['active_state'] === 'production') && is_plugin_active($plugin_file_path) && !$is_development;
    $result = null;

    if ($should_activate) {
        $result = activate_plugins($plugin['file']);
    }

    if ($should_deactivate) {
        $result = deactivate_plugins($plugin['file']);
    }

    if (is_wp_error($result)) {
        error_log($result->get_error_message());

        return false;
    }
}

add_action('plugins_init', 'jeswde_handle_plugins');
