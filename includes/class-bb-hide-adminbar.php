<?php

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

/**
 * hide admin bar for all users except admins
 * @return void
 */
function bora_bora_hide_admin_bar_for_non_admins(): void
{
    if (current_user_can('administrator')) {
        return;
    }
    show_admin_bar(false);
}

add_filter('show_admin_bar', 'bora_bora_hide_admin_bar_for_non_admins');
