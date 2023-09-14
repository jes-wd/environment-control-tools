<?php

namespace JESWD_Essentials;

require JESWD_ESSENTIALS_PLUGIN_DIR . 'includes/admin-page/form-handler.php';
require JESWD_ESSENTIALS_PLUGIN_DIR . 'includes/admin-page/renderer.php';

class Admin_Page {
    protected $renderer;

    public function __construct() {
        $this->renderer = new Renderer();

        add_action('admin_menu', [$this, 'add_menu_page']);
        add_action('admin_init', [$this, 'init_form_handler']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);        
        add_action('admin_head', [$this->renderer, 'init_color_picker']);
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

    public function init_form_handler() {
        $handler = new Form_Handler();
        $handler->handle_form_submission();
    }

    public function enqueue_admin_scripts($hook) {
        // Check if we're on the right admin page
        if ('settings_page_jeswd-essentials' !== $hook) {
            return;
        }

        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
        //enqeueu admin.css
        wp_enqueue_style('jeswd-essentials-admin', plugins_url('jeswd-essentials/assets/css/admin.css'));
    }

    public function render_options_page() {
?>
        <div class="wrap">
            <h1>JESWD Essentials</h1>
            <form method="post" action="/wp-admin/options-general.php?page=jeswd-essentials" enctype="multipart/form-data">
                <?php
                wp_nonce_field('jeswd_essentials_form_action', 'jeswd_essentials_form_nonce');
                $this->renderer->production_site_url_form();
                $this->renderer->favicon_option();
                $this->renderer->discourage_search_engines_option();
                $this->renderer->admin_bar_color_option();
                $this->renderer->plugins_options_form();
                submit_button();
                ?>
            </form>
        </div>
    <?php
    }
}
