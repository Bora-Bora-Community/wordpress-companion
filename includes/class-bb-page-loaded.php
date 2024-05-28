<?php

use BB\enum\Setting;
use BB\Service\BB_Session_Manager;

add_action('wp', 'execute_on_load_page_hook_event');

/**
 * Executes actions on page load.
 *
 * @return void
 */
function execute_on_load_page_hook_event(): void
{
    // Check if the plugin is enabled
    if (!carbon_get_theme_option(Setting::PLUGIN_ENABLED)) {
        return;
    }

    $userId = get_current_user_id();
    // Allow administrators to access all pages
    if (current_user_can('administrator')) {
        return;
    }

    $sessionManager = new BB_Session_Manager();
    $accessValidFor = carbon_get_post_meta(get_the_ID(), Setting::BORA_AVAILABLE_FOR_GROUPS);

    // Page is public or accessible to guests
    if (empty($accessValidFor) || in_array('guest', $accessValidFor)) {
        return;
    }

    // Check if user session exists and is valid
    $userSession = $sessionManager->getUserSession($userId);
    if (is_bool($userSession) && $userSession === false) {
        // Redirect to the page set in the settings for no authentication
        wp_redirect(esc_url(get_permalink(carbon_get_theme_option(Setting::REDIRECT_NO_AUTH)[0]['id'] ?? 0)));
        exit;
    }

    // User is authenticated and page is accessible to all authenticated users
    if (in_array('all', $accessValidFor)) {
        return;
    }

    // Check if user has access to the page based on their session group
    $userRole = $userSession['role'];
    if (in_array($userRole, $accessValidFor)) {
        return;
    }

    // Redirect to the page set in the settings for wrong group
    wp_redirect(esc_url(get_permalink(carbon_get_theme_option(Setting::REDIRECT_WRONG_GROUP)[0]['id'] ?? 0)));
    exit;
}
