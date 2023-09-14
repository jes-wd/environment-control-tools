<?php

namespace JESWD_Essentials;

class Renderer {
    public function admin_bar_color_option() {
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

    public function discourage_search_engines_option() {
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

    public function production_site_url_form() {
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

    public function plugins_options_form() {
        $all_plugins = get_plugins();
        $modes = [
            'dev' => 'Development Mode',
            'non_dev' => 'Non-Development Mode'
        ];
        ?>
        <h2>Plugin Options for Modes</h2>
        <table id="jeswde-plugin-options-table" class="form-table">
            <?php foreach ($all_plugins as $plugin_path => $plugin_data) : ?>
                <tr>
                    <th scope="row"><?php echo esc_html($plugin_data['Name']); ?></th>
                    <?php foreach ($modes as $mode => $mode_label): ?>
                        <?php
                            $plugins_to_activate = get_option('jeswde_plugins_to_activate_' . $mode, []);
                            $plugins_to_deactivate = get_option('jeswde_plugins_to_deactivate_' . $mode, []);
                        ?>
                        <td>
                            <h4><?php echo $mode_label; ?></h4>
                            <label>
                                <input type="radio" name="jeswde_plugin_option_<?php echo $mode; ?>[<?php echo esc_attr($plugin_path); ?>]" value="activate" <?php checked(in_array($plugin_path, $plugins_to_activate)); ?>>
                                Activate in <?php echo strtolower($mode_label); ?>
                            </label><br>
                            <label>
                                <input type="radio" name="jeswde_plugin_option_<?php echo $mode; ?>[<?php echo esc_attr($plugin_path); ?>]" value="deactivate" <?php checked(in_array($plugin_path, $plugins_to_deactivate)); ?>>
                                Deactivate in <?php echo strtolower($mode_label); ?>
                            </label><br>
                            <label>
                                <input type="radio" name="jeswde_plugin_option_<?php echo $mode; ?>[<?php echo esc_attr($plugin_path); ?>]" value="none" <?php checked(!in_array($plugin_path, $plugins_to_activate) && !in_array($plugin_path, $plugins_to_deactivate)); ?>>
                                No action
                            </label>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php
    }
    
    public function favicon_option() {
        // Get the attachment ID of the dev mode favicon
        $favicon_id = get_option('jeswde_dev_favicon');
        $favicon_url = $favicon_id ? wp_get_attachment_url($favicon_id) : '';
    ?>
        <h2>Development Mode Favicon</h2>
        <table class="form-table">
            <tr>
                <th scope="row">Upload Favicon</th>
                <td class="jeswde-favicon-container">
                    <input type="file" name="jeswde_dev_favicon" id="jeswde_dev_favicon" />
                    <?php if ($favicon_url) : ?>
                        <img src="<?php echo esc_url($favicon_url); ?>" width="32" height="32" alt="Current dev mode favicon">
                    <?php endif; ?>
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
}
