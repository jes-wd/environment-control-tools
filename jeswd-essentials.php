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

require plugin_dir_path( __FILE__ ) . 'includes/admin-page.php';

// set var to check if the site is in development mode
function jeswd_essentials_development_mode() {
    $is_development = false;
    $production_site_url = get_option( 'jeswd_essentials_production_site_url' );

    if ( $production_site_url ) {
        $production_site_url = parse_url( $production_site_url );
        $production_site_url = $production_site_url['host'];
        $current_site_url = parse_url( site_url() );
        $current_site_url = $current_site_url['host'];

        if ( $production_site_url != $current_site_url ) {
            $is_development = true;
        }
    }

    return $is_development;
}

$is_development = jeswd_essentials_development_mode();

// if the current site domain name does not match the production site url, add an orange border to the <body> tag of wp-admin
function jeswd_essentials_body_class( $classes) {
        if ( jeswd_essentials_development_mode() ) {
            $classes .= ' jeswd-essentials-admin-body-class';
        }

    return $classes;
}

// add the body class
add_filter( 'admin_body_class', 'jeswd_essentials_body_class' );
// also add to the frontend
add_filter( 'body_class', 'jeswd_essentials_body_class' );

// add the css
function jeswd_essentials_body_class_css() {
    ?>
    <style>
        body.jeswd-essentials-admin-body-class #wpadminbar {
           background: #2271b1 !important;
        }
    </style>
    <?php
}

// add the css
add_action( 'admin_head', 'jeswd_essentials_body_class_css' );
// also add to the frontend
add_action( 'wp_head', 'jeswd_essentials_body_class_css' );

// change the favicon to the JES-WD favicon
function jeswd_essentials_favicon() {
    if (jeswd_essentials_development_mode()) {
        // change the wp setting for the favicon
        // update_option( 'site_icon', 0 );
        echo '<link rel="shortcut icon" href="' . plugin_dir_url( __FILE__ ) . 'jeswd-favicon.png" />';
    }
}

// add the favicon
add_action( 'login_head', 'jeswd_essentials_favicon' );
add_action( 'admin_head', 'jeswd_essentials_favicon' );
add_action( 'wp_head', 'jeswd_essentials_favicon' );

// install and activate the Disable Emails plugin
// function jeswd_essentials_install_disable_emails() {
//     $plugin = 'disable-emails/disable-emails.php';
//     $plugin_file = WP_PLUGIN_DIR . '/' . $plugin;

//     if ( ! file_exists( $plugin_file ) ) {
//         $plugin = 'https://downloads.wordpress.org/plugin/disable-emails.zip';
//         $plugin_file = download_url( $plugin );

//         if ( is_wp_error( $plugin_file ) ) {
//             error_log( $plugin_file->get_error_message());

//             return false;
//         }

//         $result = unzip_file( $plugin_file, WP_PLUGIN_DIR );

//         if ( is_wp_error( $result ) ) {
//             error_log( $result->get_error_message());

//             return false;
//         }

//     }

//     if ( ! is_plugin_active( $plugin ) ) {
//         $result = activate_plugin( $plugin );

//         if ( is_wp_error( $result ) ) {
//             error_log( $result->get_error_message());

//             return false;
//         }
//     }
// }

// // install and activate the Disable Emails plugin
// add_action( 'admin_init', 'jeswd_essentials_install_disable_emails' );