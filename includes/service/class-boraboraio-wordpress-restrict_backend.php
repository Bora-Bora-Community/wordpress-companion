<?php

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

function boraboraio_restrict_admin_access(): void
{
    // Check if the current user is not an administrator and is trying to access the admin area
    if (!current_user_can('administrator') &&
        !current_user_can('editor') &&
        is_admin()) {
        // Redirect non-admin users to the homepage
        exit(wp_redirect(esc_url(home_url())));
    }
}

// Hook the function into 'admin_init' which runs when the admin area is initialized
add_action('admin_init', 'boraboraio_restrict_admin_access');
