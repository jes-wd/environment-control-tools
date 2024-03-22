<?php

namespace EnvironmentControlTools;

class Form_Handler {

    public function handle_form_submission() {
        if (!isset($_POST['jesect_form_nonce']) || !wp_verify_nonce(wp_unslash(sanitize_text_field($_POST['jesect_form_nonce'])), 'jesect_form_action')) {
            return;
        }

        $this->handle_production_site_url();
        $this->handle_favicon_upload();
        $this->handle_plugins_options();
        $this->handle_search_engine_visibility();
        $this->handle_admin_bar_color();
    }

    private function handle_production_site_url() {
        if (isset($_POST['jesect_production_site_url'])) {
            $jesect_production_site_url = sanitize_url($_POST['jesect_production_site_url']);
            $jesect_production_site_url = preg_replace('/(https?:\/\/)?(www\.)?/', '', $jesect_production_site_url);
            update_option('jesect_production_site_url', base64_encode($jesect_production_site_url));
        }
    }

    private function handle_favicon_upload() {
        if (isset($_FILES['jesect_dev_favicon']) && $_FILES['jesect_dev_favicon']['size'] > 0) {
            $uploaded_file = $_FILES['jesect_dev_favicon'];

            // Check for upload errors
            if ($uploaded_file['error'] === 0) {
                $upload = wp_handle_upload($uploaded_file, ['test_form' => false]);

                if (!isset($upload['error'])) {
                    $file_path = esc_url_raw($upload['file']);
                    $file_url = esc_url($upload['url']);

                    // Insert the uploaded file as an attachment
                    $attachment_id = wp_insert_attachment([
                        'guid'           => $file_url,
                        'post_mime_type' => sanitize_mime_type($upload['type']),
                        'post_title'     => sanitize_file_name(preg_replace('/\.[^.]+$/', '', basename($file_path))),
                        'post_content'   => '',
                        'post_status'    => 'inherit'
                    ], $file_path);

                    // Store the attachment ID in the WordPress option
                    update_option('jesect_dev_favicon', absint($attachment_id));
                }
            }
        }
    }

    private function handle_plugins_options() {
        $modes = ['_dev', '_production'];
    
        foreach ($modes as $mode_suffix) {
            $plugins_to_activate = [];
            $plugins_to_deactivate = [];
    
            $post_key = 'jesect_plugin_option' . $mode_suffix;
    
            if (isset($_POST[$post_key]) && is_array($_POST[$post_key])) {
                foreach ($_POST[$post_key] as $plugin_path => $action) {
                    if ($action == 'activate') {
                        $plugins_to_activate[] = sanitize_text_field($plugin_path);
                    } elseif ($action == 'deactivate') {
                        $plugins_to_deactivate[] = sanitize_text_field($plugin_path);
                    }
                }
                update_option('jesect_plugins_to_activate' . $mode_suffix, $plugins_to_activate);
                update_option('jesect_plugins_to_deactivate' . $mode_suffix, $plugins_to_deactivate);
            }
        }
    }    

    private function handle_search_engine_visibility() {
        $visibility_option = isset($_POST['jesect_discourage_search_engines']) && sanitize_text_field($_POST['jesect_discourage_search_engines']) === 'yes' ? 'yes' : 'no';
        update_option('jesect_discourage_search_engines', sanitize_key($visibility_option));
    }

    private function handle_admin_bar_color() {
        if (isset($_POST['jesect_admin_bar_color'])) {
            $admin_bar_color = sanitize_hex_color($_POST['jesect_admin_bar_color']);
            update_option('jesect_admin_bar_color', $admin_bar_color);
        }
    }
}
