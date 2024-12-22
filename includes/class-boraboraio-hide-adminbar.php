<?php

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

/**
 * hide admin bar for all users except admins
 * @return void
 */
function boraboraio_hide_admin_bar_for_non_admins(): bool
{
    return current_user_can('administrator');
}

add_filter('show_admin_bar', 'boraboraio_hide_admin_bar_for_non_admins');
