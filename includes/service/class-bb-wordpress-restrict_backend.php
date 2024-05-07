<?php

function restrict_admin_access(): void
{
    // Check if the current user is not an administrator and is trying to access the admin area
    if (!current_user_can('administrator') && is_admin()) {
        // Redirect non-admin users to the homepage
        exit(wp_redirect(esc_url(home_url())));
    }
}

// Hook the function into 'admin_init' which runs when the admin area is initialized
add_action('admin_init', 'restrict_admin_access');
