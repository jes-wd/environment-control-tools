<?php

namespace JESWD_Essentials;

class Utilities {
    public static function check_development_mode() {
        $production_site_url = base64_decode(get_option('jeswde_production_site_url'));

        if (!$production_site_url) {
            return false;
        }

        // Remove the 'www.' prefix if present
        $current_host = str_replace('www.', '', $_SERVER['HTTP_HOST']);

        if ($current_host == $production_site_url) {
            return false;
        }

        if (strpos($current_host, $production_site_url) !== false) {
            return true;
        }

        return true;
    }
}
