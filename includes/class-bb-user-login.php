<?php

use BB\API\BB_Api_Client;
use BB\enum\Setting;
use BB\Service\BB_Session_Manager;
use BB\Service\BB_User_Manager;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handles actions after user login.
 *
 * @param  string  $user_login  The user's login name.
 * @param  WP_User  $user  The WP_User object.
 *
 * @return void
 */
function bb_after_login($user_login, $user): void
{
    $bbSessionManager = new BB_Session_Manager();

    // Check if the session data exists and is valid
    $sessionData = $bbSessionManager->getUserSession($user->ID);
    if ($sessionData !== false) {
        error_log('Session data exists for user ID: '.$user->ID);
        bb_after_login_redirect($user);

        return;
    }

    error_log('No valid session found for user ID: '.$user->ID.'. Making API call.');

    // Get the user details from the Bora Bora API and update the user meta data
    $bbClient = new BB_Api_Client();
    $boraBoraId = sanitize_text_field(carbon_get_user_meta($user->ID, Setting::BORA_USER_ID));

    if (empty($boraBoraId)) {
        $userDetails = $bbClient->loadUserDetailsByMail(sanitize_email($user->user_email));
    } else {
        $userDetails = $bbClient->loadUserDetails($boraBoraId);
    }

    if (empty($userDetails) || !isset($userDetails['subscription'])) {
        error_log('User details not found for user ID: '.$user->ID);
        bb_after_login_redirect($user);

        return;
    } else {
        (new BB_User_Manager)->updateUserData($user->ID, $userDetails);
    }

    if (!in_array($userDetails['subscription']['payment_status'], ['active', 'paid', 'trialing'], true)) {
        error_log('User subscription is not active or paid for user ID: '.$user->ID);
        bb_after_login_redirect($user);

        return;
    }

    $bbSessionManager->setUserSession($user->ID, intval($userDetails['subscription']['discord_group']));
    error_log('Session data set for user ID: '.$user->ID);

    bb_after_login_redirect($user);
}

add_action('wp_login', 'bb_after_login', 10, 2);

/**
 * Redirects the user after login based on their role and settings.
 *
 * @param  WP_User  $user  The WP_User object.
 *
 * @return void
 */
function bb_after_login_redirect(WP_User $user): void
{
    // If the user is an admin, redirect to the admin dashboard
    if ($user->has_cap('administrator')) {
        wp_redirect(esc_url(admin_url()));
        exit;
    }

    // Fail early if redirects are not enabled
    if (!carbon_get_theme_option(Setting::PLUGIN_ENABLED)) {
        return;
    }

    // Redirect to a specified page after login if set, otherwise redirect to the home page
    $redirectUrl = carbon_get_theme_option(Setting::REDIRECT_AFTER_LOGIN);
    if ($redirectUrl !== null) {
        wp_redirect(esc_url(get_permalink($redirectUrl[0]['id'])));
        exit;
    }

    wp_redirect(esc_url(home_url()));
    exit;
}

/**
 * Handles actions after user logout.
 *
 * @param  int  $userId  The user ID.
 *
 * @return void
 */
function bb_after_logout($userId): void
{
    $bbSessionManager = new BB_Session_Manager();
    $bbSessionManager->deleteUserSession($userId);
}

add_action('wp_logout', 'bb_after_logout', 10, 1);
