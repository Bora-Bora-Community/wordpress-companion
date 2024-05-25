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
        error_log('Plugin is disabled.');

        return;
    }

    // Allow administrators to access all pages
    if (current_user_can('administrator')) {
        error_log('User is an administrator.');
        return;
    }

    $sessionManager = new BB_Session_Manager();
    $accessValidFor = carbon_get_post_meta(get_the_ID(), Setting::BORA_AVAILABLE_FOR_GROUPS);
    $userId = get_current_user_id();

    // Page is public
    if (empty($accessValidFor) || in_array('guest', $accessValidFor)) {
        error_log('Page is public or accessible to guests.');
        return;
    }

    // Check if user session exists and is valid
    $userSession = $sessionManager->getUserSession($userId);

    if (!$userSession) {
        error_log('User session not found or invalid for user ID: ' . $userId);
        // Redirect to the page set in the settings for no authentication
        wp_redirect(esc_url(get_permalink(carbon_get_theme_option(Setting::REDIRECT_NO_AUTH)[0]['id'] ?? 0)));
        exit;
    }

    // User is authenticated and page is accessible to all authenticated users
    if (in_array('all', $accessValidFor)) {
        error_log('Page is accessible to all authenticated users.');
        return;
    }

    // Check if user has access to the page based on their session group
    if (in_array($userSession['role'], $accessValidFor)) {
        error_log('User has access to the page.');
        return;
    }

    // Redirect to the page set in the settings for wrong group
    error_log('User does not have access to the page. Redirecting.');
    wp_redirect(esc_url(get_permalink(carbon_get_theme_option(Setting::REDIRECT_WRONG_GROUP)[0]['id'] ?? 0)));
    exit;
}
