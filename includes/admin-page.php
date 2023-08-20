<?php

class JESWD_Settings {

    public function __construct() {
        add_action('admin_menu', [$this, 'add_menu_page']);
        add_action('admin_init', [$this, 'handle_form_submission']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);
        add_action('admin_head', [$this, 'init_color_picker']);
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

    public function enqueue_admin_scripts($hook) {
        // Check if we're on the right admin page
        if ('settings_page_jeswd-essentials' !== $hook) {
            return;
        }

        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
    }

    public function render_options_page() {
?>
        <div class="wrap">
            <h1>JESWD Essentials</h1>
            <form method="post" action="/wp-admin/options-general.php?page=jeswd-essentials">
                <?php
                wp_nonce_field('jeswd_essentials_form_action', 'jeswd_essentials_form_nonce');
                $this->render_production_site_url_form();
                $this->render_discourage_search_engines_option();
                $this->render_admin_bar_color_option();
                $this->render_plugins_options_form();
                submit_button();
                ?>
            </form>
        </div>
    <?php
    }

    public function render_admin_bar_color_option() {
        $admin_bar_color = get_option('jeswde_admin_bar_color', '#2271b1'); // default color

    ?>
        <table class="form-table">
            <tr>
                <th scope="row">Admin Bar Color</th>
                <td>
                    <input type="text" name="jeswde_admin_bar_color" value="<?php echo esc_attr($admin_bar_color); ?>" class="jeswd-color-field" />
                </td>
            </tr>
        </table>
    <?php
    }

    public function init_color_picker() {
        wp_enqueue_script('jquery');
    ?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                jQuery('.jeswd-color-field').wpColorPicker();
            });
        </script>
    <?php
    }

    public function render_discourage_search_engines_option() {
        $discourage_search_engines = get_option('jeswde_discourage_search_engines', 'no');
    ?>
        <h2>Development Mode Settings</h2>
        <table class="form-table">
            <tr>
                <th scope="row">Search Engine Visibility</th>
                <td>
                    <label>
                        <input type="checkbox" name="jeswde_discourage_search_engines" value="yes" <?php checked($discourage_search_engines, 'yes'); ?>>
                        Discourage search engines from indexing this site in development mode
                    </label>
                </td>
            </tr>
        </table>
    <?php
    }


    public function render_production_site_url_form() {
    ?>
        <h2>Production Site URL</h2>
        <p>Set the production site URL here. Development mode settings will only be applied when the URL to the site does not match this URL.</p>
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
            <?php foreach ($all_plugins as $plugin_path => $plugin_data) : ?>
                <tr>
                    <th scope="row"><?php echo esc_html($plugin_data['Name']); ?></th>
                    <td>
                        <label>
                            <input type="radio" name="jeswde_plugin_option[<?php echo esc_attr($plugin_path); ?>]" value="activate" <?php checked(in_array($plugin_path, $plugins_to_activate)); ?>>
                            Activate in development mode
                        </label><br>
                        <label>
                            <input type="radio" name="jeswde_plugin_option[<?php echo esc_attr($plugin_path); ?>]" value="deactivate" <?php checked(in_array($plugin_path, $plugins_to_deactivate)); ?>>
                            Deactivate in development mode
                        </label><br>
                        <label>
                            <input type="radio" name="jeswde_plugin_option[<?php echo esc_attr($plugin_path); ?>]" value="none" <?php checked(!in_array($plugin_path, $plugins_to_activate) && !in_array($plugin_path, $plugins_to_deactivate)); ?>>
                            No action
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

            if (isset($_POST['jeswde_production_site_url'])) {
                $jeswde_production_site_url = $_POST['jeswde_production_site_url'];
                //  ensure that only a domain name or subdomain is saved, and strip all slashes and protocols
                $jeswde_production_site_url = preg_replace('/(https?:\/\/)?(www\.)?/', '', $jeswde_production_site_url);
        
                update_option('jeswde_production_site_url', base64_encode($jeswde_production_site_url));
            }

            // plugin activation/deactivation
            $plugins_to_activate = [];
            $plugins_to_deactivate = [];

            if (isset($_POST['jeswde_plugin_option']) && is_array($_POST['jeswde_plugin_option'])) {
                foreach ($_POST['jeswde_plugin_option'] as $plugin_path => $action) {
                    if ($action == 'activate') {
                        $plugins_to_activate[] = sanitize_text_field($plugin_path);
                    } elseif ($action == 'deactivate') {
                        $plugins_to_deactivate[] = sanitize_text_field($plugin_path);
                    }
                }
            }

            update_option('jeswde_plugins_to_activate', $plugins_to_activate);
            update_option('jeswde_plugins_to_deactivate', $plugins_to_deactivate);

            // search engine visibility
            if (isset($_POST['jeswde_discourage_search_engines']) && $_POST['jeswde_discourage_search_engines'] === 'yes') {
                update_option('jeswde_discourage_search_engines', 'yes');
            } else {
                update_option('jeswde_discourage_search_engines', 'no');
            }

            // admin bar color
            if (isset($_POST['jeswde_admin_bar_color'])) {
                $admin_bar_color = sanitize_text_field($_POST['jeswde_admin_bar_color']);
                update_option('jeswde_admin_bar_color', $admin_bar_color);
            }
        }
    }
}

new JESWD_Settings();
