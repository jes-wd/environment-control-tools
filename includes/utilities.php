<?php

namespace EnvironmentControlTools;

class Utilities {
    public static function check_development_mode() {
        $encoded_production_site_url = get_option('jesect_production_site_url');
        if (!$encoded_production_site_url) {
            return false;
        }

        $production_site_url = base64_decode(sanitize_text_field($encoded_production_site_url));

        if (!$production_site_url) {
            return false;
        }

        // Remove the 'www.' prefix if present
        $current_host = str_replace('www.', '', sanitize_text_field($_SERVER['HTTP_HOST']));

        if ($current_host === $production_site_url) {
            return false;
        }

        if (strpos($current_host, $production_site_url) !== false) {
            return true;
        }

        return true;
    }
}
