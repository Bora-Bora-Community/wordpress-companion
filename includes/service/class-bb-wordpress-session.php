<?php

use BB\enum\Setting;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

/**
 * @param  int  $expiration
 * @param  int  $user_id
 * @param  bool  $remember
 *
 * @return int session length
 */
function bb_wordpress_session_length(int $expiration, int $user_id, bool $remember): int
{
    return (int) carbon_get_theme_option(Setting::SESSION_LENGTH) ?? YEAR_IN_SECONDS;
}

function bb_load_custom_session_length(): void
{
    if (carbon_get_theme_option(Setting::SESSION_LENGTH_ACTIVE) === 'yes') {
        add_filter('auth_cookie_expiration', 'bb_wordpress_session_length', 99, 3);
    }
}

add_action('carbon_fields_register_fields', 'bb_load_custom_session_length');

function bb_custom_login_settings()
{
    echo '
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("rememberme").checked = true;
            document.getElementById("user_login").focus();
        });
    </script>
    ';
}

add_filter('login_footer', 'bb_custom_login_settings');
