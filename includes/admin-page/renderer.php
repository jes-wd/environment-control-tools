<?php

namespace EnvironmentControlTools;

class Renderer {
    public function admin_bar_color_option() {
        $admin_bar_color = get_option('ect_admin_bar_color', '#2271b1'); // default color

?>
        <table class="form-table">
            <tr>
                <th scope="row">Admin Bar Color</th>
                <td>
                    <input type="text" name="ect_admin_bar_color" value="<?php echo esc_attr($admin_bar_color); ?>" class="jes-color-field" />
                </td>
            </tr>
        </table>
    <?php
    }

    public function discourage_search_engines_option() {
        $discourage_search_engines = get_option('ect_discourage_search_engines', 'no');
    ?>
        <h2>Development Mode Settings</h2>
        <table class="form-table">
            <tr>
                <th scope="row">Search Engine Visibility</th>
                <td>
                    <label>
                        <input type="checkbox" name="ect_discourage_search_engines" value="yes" <?php checked($discourage_search_engines, 'yes'); ?>>
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
                <th scope="row"><label for="ect_production_site_url">Production Site URL</label></th>
                <td><input name="ect_production_site_url" type="text" id="ect_production_site_url" value="<?php echo base64_decode(get_option('ect_production_site_url', '')); ?>" class="regular-text"></td>
            </tr>
        </table>
    <?php
    }

    public function plugins_options_form() {
        $all_plugins = get_plugins();
        $modes = [
            'dev' => 'Development Mode',
            'production' => 'Production Mode'
        ];
        ?>
        <h2>Plugin Options</h2>
        <p>These options allow you to control the active state of plugins when your site is in development mode and non-development mode. When options are set to other than "No action", the active state of the plugin will be controlled by this plugin when on the given mode, and you will see "Controlled by Environment Control Tools" in place of the activation/deactivation links on the plugins page.</p>
        <table id="ect-plugin-options-table" class="form-table">
            <?php foreach ($all_plugins as $plugin_path => $plugin_data) : ?>
                <tr>
                    <th scope="row"><?php echo esc_html($plugin_data['Name']); ?></th>
                    <?php foreach ($modes as $mode => $mode_label): ?>
                        <?php
                            $plugins_to_activate = get_option('ect_plugins_to_activate_' . $mode, []);
                            $plugins_to_deactivate = get_option('ect_plugins_to_deactivate_' . $mode, []);
                        ?>
                        <td>
                            <h4><?php echo $mode_label; ?></h4>
                            <label>
                                <input type="radio" name="ect_plugin_option_<?php echo $mode; ?>[<?php echo esc_attr($plugin_path); ?>]" value="activate" <?php checked(in_array($plugin_path, $plugins_to_activate)); ?>>
                                Activate in <?php echo strtolower($mode_label); ?>
                            </label><br>
                            <label>
                                <input type="radio" name="ect_plugin_option_<?php echo $mode; ?>[<?php echo esc_attr($plugin_path); ?>]" value="deactivate" <?php checked(in_array($plugin_path, $plugins_to_deactivate)); ?>>
                                Deactivate in <?php echo strtolower($mode_label); ?>
                            </label><br>
                            <label>
                                <input type="radio" name="ect_plugin_option_<?php echo $mode; ?>[<?php echo esc_attr($plugin_path); ?>]" value="none" <?php checked(!in_array($plugin_path, $plugins_to_activate) && !in_array($plugin_path, $plugins_to_deactivate)); ?>>
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
        $favicon_id = get_option('ect_dev_favicon');
        $favicon_url = $favicon_id ? wp_get_attachment_url($favicon_id) : '';
    ?>
        <h2>Development Mode Favicon</h2>
        <table class="form-table">
            <tr>
                <th scope="row">Upload Favicon</th>
                <td class="ect-favicon-container">
                    <input type="file" name="ect_dev_favicon" id="ect_dev_favicon" />
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
                jQuery('.jes-color-field').wpColorPicker();
            });
        </script>
<?php
    }
}
