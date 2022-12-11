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

// add a menu page in the settings menu
function jeswd_essentials_menu() {
    add_options_page(
        'JESWD Essentials',
        'JESWD Essentials',
        'manage_options',
        'jeswd-essentials',
        'jeswd_essentials_options_page'
    );
}

// add the menu page
add_action( 'admin_menu', 'jeswd_essentials_menu' );

// add the settings page
function jeswd_essentials_options_page() {
    ?>
    <div class="wrap">
        <h1>JESWD Essentials</h1>
        <p>Here are some useful functions for JES-WD</p>
    </div>
    <?php
    // allow the user to set a production site url
    jeswd_essentials_production_site_url();

    

}

// allow the user to set a production site url
function jeswd_essentials_production_site_url() {
    ?>
    <h2>Production Site URL</h2>
    <p>Set the production site URL here. This will be used to redirect the site to the production site if the site is not in development mode.</p>
    <form method="post" action="options.php">
        <?php
        settings_fields( 'jeswd-essentials-production-site-url' );
        do_settings_sections( 'jeswd-essentials-production-site-url' );
        ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">Production Site URL</th>
                <td><input type="text" name="jeswd_essentials_production_site_url" value="<?php echo esc_attr( get_option( 'jeswd_essentials_production_site_url' ) ); ?>" /></td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>
    <?php
}

// add the settings
function jeswd_essentials_settings() {
    register_setting( 'jeswd-essentials-production-site-url', 'jeswd_essentials_production_site_url' );
}

// add the settings
add_action( 'admin_init', 'jeswd_essentials_settings' );

// if the current site domain name does not match the production site url, add an orange border to the <body> tag of wp-admin
function jeswd_essentials_admin_body_class( $classes ) {
    $production_site_url = get_option( 'jeswd_essentials_production_site_url' );
    if ( $production_site_url ) {
        $production_site_url = parse_url( $production_site_url );
        $production_site_url = $production_site_url['host'];
        $current_site_url = parse_url( site_url() );
        $current_site_url = $current_site_url['host'];
        if ( $production_site_url != $current_site_url ) {
            $classes .= ' jeswd-essentials-admin-body-class';
        }
    }
    return $classes;
}

// add the body class
add_filter( 'admin_body_class', 'jeswd_essentials_admin_body_class' );

// add the css
function jeswd_essentials_admin_body_class_css() {
    ?>
    <style>
        body.jeswd-essentials-admin-body-class::before {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            content: '';
            z-index: 999999;
            border: 3px solid orange !important;
        }
    </style>
    <?php
}

// add the css
add_action( 'admin_head', 'jeswd_essentials_admin_body_class_css' );

