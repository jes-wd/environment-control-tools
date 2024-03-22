<?php

namespace EnvironmentControlTools;

require JESECT_PLUGIN_DIR . 'includes/admin-page/form-handler.php';
require JESECT_PLUGIN_DIR . 'includes/admin-page/renderer.php';

class Admin_Page {
    protected $renderer;

    public function __construct() {
        $this->renderer = new Renderer();

        add_action('admin_menu', [$this, 'add_menu_page']);
        add_action('admin_init', [$this, 'init_form_handler']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);
    }

    public function add_menu_page() {
        add_options_page(
            'Environment Control',
            'Environment Control Tools',
            'manage_options',
            JESECT_PLUGIN_SLUG,
            [$this, 'render_options_page']
        );
    }

    public function init_form_handler() {
        $handler = new Form_Handler();
        $handler->handle_form_submission();
    }

    public function enqueue_admin_scripts($hook) {
        // Check if we're on the right admin page
        if ('settings_page_' . JESECT_PLUGIN_SLUG !== $hook) {
            return;
        }

        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
        //enqeueu admin.css
        $style_path = JESECT_PLUGIN_DIR . 'assets/css/admin.css'; // Assuming JESECT_PLUGIN_DIR points to the plugin directory
        wp_enqueue_style('environment-control-tools-admin', JESECT_PLUGIN_URL . 'assets/css/admin.css', [], filemtime($style_path));
        wp_enqueue_script('jquery');
        wp_enqueue_script('environment-control-tools-admin', JESECT_PLUGIN_URL . 'assets/js/admin.js', ['jquery'], filemtime(JESECT_PLUGIN_DIR . 'assets/js/admin.js'), true);
    }

    public function render_options_page() {
?>
        <div class="wrap">
            <h1>Environment Control Tools</h1>
            <form method="post" action="/wp-admin/options-general.php?page=environment-control-tools" enctype="multipart/form-data">
                <?php
                wp_nonce_field('jesect_form_action', 'jesect_form_nonce');
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
