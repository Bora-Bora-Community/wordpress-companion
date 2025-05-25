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
    if (current_user_can('administrator') || current_user_can('editor')) {
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

    // user not logged in - redirect to login page
    $redirect_no_auth_id = carbon_get_theme_option(Boraboraio_Setting::BORA_BORA_IO_REDIRECT_NO_AUTH)[0]['id'] ?? 0;
    $redirect_no_auth_url = esc_url(get_permalink($redirect_no_auth_id));
    if ($userId === 0) {
        wp_redirect($redirect_no_auth_url);
        exit;
    }

    $userSession = $sessionManager->getUserSession($userId);

    // If the session does not exist or is invalid, reload the information from the Bora Bora API
    $redirect_no_auth_id = carbon_get_theme_option(Boraboraio_Setting::BORA_BORA_IO_REDIRECT_NO_AUTH)[0]['id'] ?? 0;
    $redirect_no_auth_url = esc_url(get_permalink($redirect_no_auth_id));
    if ($userSession === false || !boraboraio_subscriptionStatusIsActiveOrTrialing($userId)) {
        $userSession = boraboraio_refreshUserDataFromAPI($userId, $redirect_no_auth_url, $sessionManager, $userSession);

        // no user session found - redirect to login page
        if ($userSession === false) {
            wp_redirect($redirect_no_auth_url);
        }
    }

    // check subscription status, refresh of the database was just right now
    if (!boraboraio_subscriptionStatusIsActiveOrTrialing($userId)) {
        error_log('Users subscription not active anymore: '.$userId);
        $redirect_no_active_subscription = carbon_get_theme_option(Boraboraio_Setting::BORA_BORA_IO_REDIRECT_INACTIVE_SUBSCRIPTION)[0]['id'] ?? 0;
        $redirect_no_active_subscription = esc_url(get_permalink($redirect_no_active_subscription));
        wp_redirect($redirect_no_active_subscription);
        exit;
    }

    // User is authenticated and page is accessible to all authenticated users
    if (in_array('all', $accessValidFor)) {
        return;
    }

    // Check if user has access to the page based on their session group
    $userRole = $userSession['role'] ?? null;
    if ($userRole !== null && in_array($userRole, $accessValidFor)) {
        return;
    }

    // when we get to this point, the user is not allowed to access the page
    // Validate and sanitize redirect URL for wrong group
    $redirect_wrong_group_id = carbon_get_theme_option(Boraboraio_Setting::BORA_BORA_IO_REDIRECT_WRONG_GROUP)[0]['id'] ?? 0;
    $redirect_wrong_group_url = esc_url(get_permalink($redirect_wrong_group_id));

    // Redirect to the page set in the settings for wrong group
    wp_redirect($redirect_wrong_group_url);
    exit;
}

/**
 * @param  int  $userId
 *
 * @return bool
 */
function boraboraio_subscriptionStatusIsActiveOrTrialing(int $userId): bool
{
    return in_array(sanitize_text_field(carbon_get_user_meta($userId,
        Boraboraio_Setting::BORA_BORA_IO_USER_SUBSCRIPTION_STATUS)), ['active', 'trialing']);
}

/**
 * @param  int  $userId
 * @param  string  $redirect_no_auth_url
 * @param  \Boraboraio\Service\Boraboraio_Session_Manager  $sessionManager
 * @param  bool|array  $userSession
 *
 * @return array|bool|void
 */
function boraboraio_refreshUserDataFromAPI(
    int $userId,
    string $redirect_no_auth_url,
    Boraboraio_Session_Manager $sessionManager,
    bool|array $userSession
) {
    $bbClient = new Boraboraio_Api_Client();
    $boraBoraId = sanitize_text_field(carbon_get_user_meta($userId, Boraboraio_Setting::BORA_BORA_IO_USER_ID));
    $userDetails = $bbClient->loadUserDetails($boraBoraId);

    if (empty($userDetails) || !isset($userDetails['subscription'])) {
        error_log('User details not found for user ID: '.$userId);

        return false;
    } else {
        (new Boraboraio_User_Manager)->updateUserData($userId, $userDetails);

        // Update the session with the new data
        if ($sessionManager->setUserSession($userId, intval($userDetails['subscription']['discord_group']))) {
            $userSession = $sessionManager->getUserSession($userId);
        }
    }

    return $userSession;
}
