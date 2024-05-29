<?php

use BB\API\BB_Api_Client;
use BB\enum\Setting;
use BB\Service\BB_Session_Manager;
use BB\Service\BB_User_Manager;

add_action('wp', 'execute_on_load_page_hook_event');

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

function execute_on_load_page_hook_event(): void
{
    // Check if the plugin is enabled
    if (!carbon_get_theme_option(Setting::PLUGIN_ENABLED)) {
        return;
    }

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
    $userId = get_current_user_id();
    $userSession = $sessionManager->getUserSession($userId);
    if (is_bool($userSession) && $userSession === false) {
        // here we need to reload the information from the Bora Bora API
        $bbClient = new BB_Api_Client();
        $boraBoraId = sanitize_text_field(carbon_get_user_meta($userId, Setting::BORA_USER_ID));
        $userDetails = $bbClient->loadUserDetails($boraBoraId);

        if (empty($userDetails) || !isset($userDetails['subscription'])) {
            error_log('User details not found for user ID: '.$userId);
            $redirect_no_auth_id = carbon_get_theme_option(Setting::REDIRECT_NO_AUTH)[0]['id'] ?? 0;
            $redirect_no_auth_url = esc_url(get_permalink($redirect_no_auth_id));
            wp_redirect($redirect_no_auth_url);

            exit;
        } else {
            (new BB_User_Manager)->updateUserData($userId, $userDetails);
            if ($sessionManager
                ->setUserSession($userId, intval($userDetails['subscription']['discord_group']))) {
                $userSession = $sessionManager->getUserSession($userId);

            }
        }
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

    // Validate and sanitize redirect URL for wrong group
    $redirect_wrong_group_id = carbon_get_theme_option(Setting::REDIRECT_WRONG_GROUP)[0]['id'] ?? 0;
    $redirect_wrong_group_url = esc_url(get_permalink($redirect_wrong_group_id));

    // Redirect to the page set in the settings for wrong group
    wp_redirect($redirect_wrong_group_url);
    exit;
}
