<?php

use BB\enum\Setting;

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

add_filter('auth_cookie_expiration', 'bb_wordpress_session_length', 99, 3);
