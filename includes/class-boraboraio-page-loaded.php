<?php

use Boraboraio\API\Boraboraio_Api_Client;
use Boraboraio\enum\Boraboraio_Setting;
use Boraboraio\Service\Boraboraio_Session_Manager;
use Boraboraio\Service\Boraboraio_User_Manager;

add_action('wp', 'boraboraio_execute_on_load_page_hook_event');

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Executes actions on page load.
 *
 * @return void
 */
function boraboraio_execute_on_load_page_hook_event(): void
{
    // Check if the plugin is enabled
    if (!carbon_get_theme_option(Boraboraio_Setting::BORA_BORA_IO_PLUGIN_ENABLED)) {
        return;
    }

    // Allow administrators to access all pages
    if (current_user_can('administrator')) {
        return;
    }

    $sessionManager = new Boraboraio_Session_Manager();
    $accessValidFor = carbon_get_post_meta(get_the_ID(), Boraboraio_Setting::BORA_BORA_IO_AVAILABLE_FOR_GROUPS);

    // Page is public or accessible to guests
    if (empty($accessValidFor) || in_array('guest', $accessValidFor)) {
        return;
    }

    // Check if user session exists and is valid
    $userId = get_current_user_id();
    $userSession = $sessionManager->getUserSession($userId);

    // If the session does not exist or is invalid, reload the information from the Bora Bora API
    $redirect_no_auth_id = carbon_get_theme_option(Boraboraio_Setting::BORA_BORA_IO_REDIRECT_NO_AUTH)[0]['id'] ?? 0;
    $redirect_no_auth_url = esc_url(get_permalink($redirect_no_auth_id));
    if ($userSession === false) {
        $bbClient = new Boraboraio_Api_Client();
        $boraBoraId = sanitize_text_field(carbon_get_user_meta($userId, Boraboraio_Setting::BORA_BORA_IO_USER_ID));
        $userDetails = $bbClient->loadUserDetails($boraBoraId);

        if (empty($userDetails) || !isset($userDetails['subscription'])) {
            error_log('User details not found for user ID: '.$userId);
            wp_redirect($redirect_no_auth_url);
            exit;
        } else {
            (new Boraboraio_User_Manager)->updateUserData($userId, $userDetails);

            // Update the session with the new data
            if ($sessionManager->setUserSession($userId, intval($userDetails['subscription']['discord_group']))) {
                $userSession = $sessionManager->getUserSession($userId);
            }
        }
    }

    // check subscription status
    if (!in_array(sanitize_text_field(carbon_get_user_meta($userId, Boraboraio_Setting::BORA_BORA_IO_USER_SUBSCRIPTION_STATUS)),
        ['active', 'trialing'])) {
        error_log('Users subscription not active anymore: '.$userId);
        wp_redirect($redirect_no_auth_url);
        exit;
    }

    // User is authenticated and page is accessible to all authenticated users
    if (in_array('all', $accessValidFor)) {
        return;
    }

    // Check if user has access to the page based on their session group
    $userRole = $userSession['role'] ?? null; // Added null coalescing operator for safety
    if ($userRole !== null && in_array($userRole, $accessValidFor)) {
        return;
    }

    // Validate and sanitize redirect URL for wrong group
    $redirect_wrong_group_id = carbon_get_theme_option(Boraboraio_Setting::BORA_BORA_IO_REDIRECT_WRONG_GROUP)[0]['id'] ?? 0;
    $redirect_wrong_group_url = esc_url(get_permalink($redirect_wrong_group_id));

    // Redirect to the page set in the settings for wrong group
    wp_redirect($redirect_wrong_group_url);
    exit;
}
