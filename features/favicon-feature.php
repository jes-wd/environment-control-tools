<?php

namespace EnvironmentControlTools;

class Favicon_Feature {
    private $is_development;

    public function __construct($is_development) {
        $this->is_development = $is_development;
        $this->manage_favicon();
    }

    private function manage_favicon() {
        if ($this->is_development) {
            $this->set_dev_favicon();
        } else {
            $this->restore_original_favicon();
        }
    }

    private function set_dev_favicon() {
        // Get the current site_icon
        $current_site_icon = get_option('site_icon');

        // Save the current site icon to our custom option, if it's not already saved
        if (false === get_option('ect_original_site_icon') && $current_site_icon) {
            update_option('ect_original_site_icon', $current_site_icon);
        }

        // Get the attachment ID of our uploaded favicon
        $our_favicon_attachment_id = get_option('ect_dev_favicon');

        // If we have an uploaded favicon, set it as the site icon
        if ($our_favicon_attachment_id) {
            update_option('site_icon', $our_favicon_attachment_id);
        }
    }

    private function restore_original_favicon() {
        // Retrieve the original site_icon saved in our custom option
        $original_site_icon = get_option('ect_original_site_icon');

        // If we have an original site icon saved, restore it
        if ($original_site_icon) {
            update_option('site_icon', $original_site_icon);
            delete_option('ect_original_site_icon'); // Optional: delete our custom option after restoring
        }
    }
}