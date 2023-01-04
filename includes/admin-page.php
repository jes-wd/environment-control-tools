
<?php

// add a menu page in the settings menu
function jeswde_menu() {
    add_options_page(
        'JESWD Essentials',
        'JESWD Essentials',
        'manage_options',
        'jeswd-essentials',
        'jeswde_options_page'
    );
}

// add the menu page
add_action( 'admin_menu', 'jeswde_menu' );

// add the settings page
function jeswde_options_page() {
    ?>
    <div class="wrap">
        <h1>JESWD Essentials</h1>
    </div>
    <?php
    // allow the user to set a production site url
    jeswde_production_site_url();
}

// allow the user to set a production site url
function jeswde_production_site_url() {
    ?>
    <h2>Production Site URL</h2>
    <p>Set the production site URL here.</p>
    <form method="post" action="/wp-admin/options-general.php?page=jeswd-essentials">
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="page_options" value="jeswde_production_site_url">
        <table class="form-table">
            <tr>
                <th scope="row"><label for="jeswde_production_site_url">Production Site URL</label></th>
                <td><input name="jeswde_production_site_url" type="text" id="jeswde_production_site_url" value="<?php echo base64_decode(get_option('jeswde_production_site_url')); ?>" class="regular-text"></td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>
    <?php
}

function jeswd_handle_form() {
    // // delete option jeswd_essentials_production_site_url
    //     delete_option('jeswd_essentials_production_site_url');

    if (isset($_POST['jeswde_production_site_url'])) {
        $jeswde_production_site_url = $_POST['jeswde_production_site_url'];

        update_option('jeswde_production_site_url', base64_encode($jeswde_production_site_url));
    }
}

add_action('admin_init', 'jeswd_handle_form');

// when the user saves the settings, base64 encode the jeswde_production_site_url field
// function jeswde_save_settings() {
//     echo 'jeswde_save_settings';

//     $jeswde_production_site_url = get_option('jeswde_production_site_url');
//     $jeswde_production_site_url_encoded = base64_encode($jeswde_production_site_url);
    
//     update_option('jeswde_production_site_url', $jeswde_production_site_url_encoded);
// }

// run the function when the user saves the settings
// add_action('update_option_jeswde_production_site_url', 'jeswde_save_settings');

// add the settings
// function jeswde_settings() {
//     register_setting( 'jeswd-essentials-production-site-url', 'jeswde_production_site_url' );
// }

// // add the settings
// add_action( 'admin_init', 'jeswde_settings' );