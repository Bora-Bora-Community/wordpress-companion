<?php

use BB\API\BB_Api_Client;
use BB\enum\Setting;
use BB\Service\BB_Session_Manager;
use BB\Service\BB_User_Manager;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

/**
 * Handles actions after user login.
 *
 * @param string $user_login The user's login name.
 * @param WP_User $user The WP_User object.
 * @return void
 */
function bb_after_login($user_login, $user): void
{
    $bbSessionManager = new BB_Session_Manager();

    // Check if the session data exists and is valid
    $sessionData = $bbSessionManager->getUserSession($user->ID);
    if ($sessionData !== false) {
        bb_after_login_redirect($user);
        return;
    }

    // Get the user details from the Bora Bora API and update the user meta data
    $bbClient = new BB_Api_Client();
    $boraBoraId = carbon_get_user_meta($user->ID, Setting::BORA_USER_ID);

    // Try to get Bora Bora user details by email if Bora Bora ID is not set
    if (empty($boraBoraId)) {
        $userDetails = $bbClient->loadUserDetailsByMail($user->user_email);
    } else {
        $userDetails = $bbClient->loadUserDetails($boraBoraId);
    }

    // If no user details are found, redirect the user
    if (empty($userDetails) || !isset($userDetails['subscription'])) {
        bb_after_login_redirect($user);
        return;
    } else {
        // Update the user metadata with the received data from the Bora Bora API
        (new BB_User_Manager)->updateUserData($user->ID, $userDetails);
    }

    // Only set the session data if the user has a subscription with payment status "active", "paid", or "trialing"
    if (!in_array($userDetails['subscription']['payment_status'], ['active',  'trialing'], true)) {
        bb_after_login_redirect($user);
        return;
    }

    // Set the session data as the subscription is active and paid
    // Allow full access to the booked contents
    $bbSessionManager->setUserSession($user->ID, $userDetails['subscription']['discord_group']);

    bb_after_login_redirect($user);
}

add_filter('wp_login', 'bb_after_login', 10, 2);

/**
 * Redirects the user after login based on their role and settings.
 *
 * @param WP_User $user The WP_User object.
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
 * @param int $userId The user ID.
 * @return void
 */
function bb_after_logout($userId): void
{
    $bbSessionManager = new BB_Session_Manager();
    $bbSessionManager->deleteUserSession(userId: $userId);
}

add_filter('wp_logout', 'bb_after_logout', 10, 1);
