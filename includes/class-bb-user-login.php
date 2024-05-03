<?php

use BB\API\BB_Api_Client;
use BB\enum\Setting;
use BB\Service\BB_Session_Manager;
use BB\Service\BB_User_Manager;

/**
 * @param $user_login
 * @param $user
 *
 * @return void
 */
function bb_after_login($user_login, $user): void
{
//    ray($user);
    $bbSessionManager = new BB_Session_Manager();
    // session cookie exists and is valid
    if ($bbSessionManager->checkUserSessionExists($user->ID)) {
//        ray('session cookie exists and is valid');
        bb_after_login_redirect(user: $user);
    }

    // get the user details from the bora bora api
    // and update the user meta data
    // create a new session cookie. time frame length is BORA_BORA_SESSION_VALID_TIMEFRAME_IN_HOURS
    $bbClient = new BB_Api_Client();
    $boraBoraId = carbon_get_user_meta($user->ID, Setting::BORA_USER_ID);

    if ($boraBoraId == [] || $boraBoraId === '') {
        // bora id not yet set. try to get it by email
        $userDetails = $bbClient->loadUserDetailsByMail(userEmail: $user->user_email);
    } else {
        $userDetails = $bbClient->loadUserDetails(boraBoraId: $boraBoraId);
    }
//    ray([$userDetails, $boraBoraId]);
    if ($userDetails === []) {
        bb_after_login_redirect(user: $user);
    }

    // update the user metadata with the received data from the Bora Bora API
    (new BB_User_Manager)->updateUserData(userId: $user->ID, data: $userDetails);

    // only set the session cookie if the user has a subscription with payment_status "active" or "paid"
    if ($userDetails['subscription']['payment_status'] !== 'active'
        && $userDetails['subscription']['payment_status'] !== 'paid'
        && $userDetails['subscription']['payment_status'] !== 'trialing'
    ) {
        bb_after_login_redirect(user: $user);
    }

    // set the session cookie as the subscription is active & paid
    // allow full access to the booked contents
//    ray(['userDetails' => 'bb_discord_role_'.$user->ID]);
    $bbSessionManager->setTransient(role: 'bb_discord_role_'.$user->ID,
                                    data: $userDetails['subscription']['discord_group']);
    bb_after_login_redirect(user: $user);
}

add_filter(hook_name: 'wp_login', callback: 'bb_after_login', priority: 10, accepted_args: 2);

function bb_after_login_redirect(WP_User $user): void
{
    // if admin user, don't redirect
    if ($user->has_cap('administrator')) {
//        ray('admin user, do nothing');

        return;
    }
    // fail early if redirects are not enabled
    if (!carbon_get_theme_option(Setting::PLUGIN_ENABLED)) {
//        ray('redirect is not enabled, do nothing');

        return;
    }
//    ray('redirect if route is set');
    if (carbon_get_theme_option(Setting::REDIRECT_AFTER_LOGIN) !== null) {
//        ray('redirect to route');
        exit(wp_redirect(esc_url(get_permalink(carbon_get_theme_option(Setting::REDIRECT_AFTER_LOGIN)[0]['id']))));
    }
//    ray('redirect to home');
    exit(wp_redirect(esc_url(home_url())));
}

/**
 * @param $userId
 *
 * @return void
 */
function bb_after_logout($userId): void
{
    $bbSessionManager = new BB_Session_Manager();
    $bbSessionManager->deleteUserSession(userId: $userId);
}

add_filter(hook_name: 'wp_logout', callback: 'bb_after_logout', priority: 10, accepted_args: 1);
