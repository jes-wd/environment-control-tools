<?php

class JESWD_Settings {

    public function __construct() {
        add_action('admin_menu', [$this, 'add_menu_page']);
        add_action('admin_init', [$this, 'handle_form_submission']);
    }

    public function add_menu_page() {
        add_options_page(
            'JESWD Essentials',
            'JESWD Essentials',
            'manage_options',
            'jeswd-essentials',
            [$this, 'render_options_page']
        );
    }

    public function render_options_page() {
        ?>
        <div class="wrap">
            <h1>JESWD Essentials</h1>
            <form method="post" action="/wp-admin/options-general.php?page=jeswd-essentials">
                <?php
                wp_nonce_field('jeswd_essentials_form_action', 'jeswd_essentials_form_nonce');
                $this->render_production_site_url_form();
                $this->render_plugins_options_form();
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public function render_production_site_url_form() {
        ?>
        <h2>Production Site URL</h2>
        <p>Set the production site URL here.</p>
        <table class="form-table">
            <tr>
                <th scope="row"><label for="jeswde_production_site_url">Production Site URL</label></th>
                <td><input name="jeswde_production_site_url" type="text" id="jeswde_production_site_url" value="<?php echo base64_decode(get_option('jeswde_production_site_url', '')); ?>" class="regular-text"></td>
            </tr>
        </table>
        <?php
    }

    public function render_plugins_options_form() {
        $all_plugins = get_plugins();
        $plugins_to_activate = get_option('jeswde_plugins_to_activate', []);
        $plugins_to_deactivate = get_option('jeswde_plugins_to_deactivate', []);
        ?>
        <h2>Plugin Options for Development Mode</h2>
        <table class="form-table">
            <?php foreach ($all_plugins as $plugin_path => $plugin_data): ?>
                <tr>
                    <th scope="row"><?php echo esc_html($plugin_data['Name']); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="jeswde_plugins_to_activate[]" value="<?php echo esc_attr($plugin_path); ?>" <?php checked(in_array($plugin_path, $plugins_to_activate)); ?>>
                            Activate in development mode
                        </label><br>
                        <label>
                            <input type="checkbox" name="jeswde_plugins_to_deactivate[]" value="<?php echo esc_attr($plugin_path); ?>" <?php checked(in_array($plugin_path, $plugins_to_deactivate)); ?>>
                            Deactivate in development mode
                        </label>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php
    }

    public function handle_form_submission() {
        if (isset($_POST['jeswd_essentials_form_nonce'])) {
            // If nonce verification fails, return early.
            if (!wp_verify_nonce($_POST['jeswd_essentials_form_nonce'], 'jeswd_essentials_form_action')) {
                return;
            }
    
            if (isset($_POST['jeswde_plugins_to_activate'])) {
                update_option('jeswde_plugins_to_activate', $_POST['jeswde_plugins_to_activate']);
            } else {
                update_option('jeswde_plugins_to_activate', []);
            }
    
            if (isset($_POST['jeswde_plugins_to_deactivate'])) {
                update_option('jeswde_plugins_to_deactivate', $_POST['jeswde_plugins_to_deactivate']);
            } else {
                update_option('jeswde_plugins_to_deactivate', []);
            }
        }
    }
    
}

new JESWD_Settings();
