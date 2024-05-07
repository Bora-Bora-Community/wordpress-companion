<?php

use BB\enum\Setting;

function bb_wordpress_session_length(): int
{
    return carbon_get_theme_option(Setting::SESSION_LENGTH) ?? 31536000;
}

add_filter('auth_cookie_expiration', 'bb_wordpress_session_length', 99);
