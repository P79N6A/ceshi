<?php

class CSS {
    public static function getCur($href) {
        // hack
        if (is_array($href)) {
            foreach ($href as $row) {
                if (strpos(parse_url(get_current_page_url(), PHP_URL_PATH), $row) !== false) {
                    return 'cur';
                }
            }
            return '';
        }


        if (strpos(parse_url(get_current_page_url(), PHP_URL_PATH), $href) !== false) {
            return "cur";
        }
        return '';
    }

}