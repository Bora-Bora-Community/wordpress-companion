<?php

use Boraboraio\enum\Setting;

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
function boraboraio_wordpress_session_length(int $expiration, int $user_id, bool $remember): int
{
    return (int) carbon_get_theme_option(Setting::SESSION_LENGTH) ?? YEAR_IN_SECONDS;
}

function boraboraio_load_custom_session_length(): void
{
    if (carbon_get_theme_option(Setting::SESSION_LENGTH_ACTIVE) === 'yes') {
        add_filter('auth_cookie_expiration', 'boraboraio_wordpress_session_length', 99, 3);
    }
}

add_action('carbon_fields_register_fields', 'boraboraio_load_custom_session_length');

// Function to enqueue the login settings script
function boraboraio_login_enqueue_scripts(): void
{
    $plugin_url = plugin_dir_url(dirname(__FILE__, 2));
    $script_path = $plugin_url.'public/js/login-settings.js';

    wp_enqueue_script(
        'bora-bora-login-settings',
        $script_path,
        [],
        '1.0.0',
        true
    );
}

add_action('login_enqueue_scripts', 'boraboraio_login_enqueue_scripts');
