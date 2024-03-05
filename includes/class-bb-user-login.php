<?php

use BB\API\BB_Api_Client;
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
    $bbSessionManager = new BB_Session_Manager();
    // session cookie exists and is valid
    if ($bbSessionManager->checkTransientExistsAndIsValid(role: 'bb_discord_role')) {
        return;
    }

    // get the user details from the bora bora api
    // and update the user meta data
    // create a new session cookie. time frame length is BORA_BORA_SESSION_VALID_TIMEFRAME_IN_HOURS

    $bbClient = new BB_Api_Client();
    $boraBoraId = carbon_get_user_meta($user->ID, 'bora_bora_id');

    if ($boraBoraId == []) {
        return;
    }
    $userDetails = $bbClient->loadUserDetails(boraBoraId: $boraBoraId);

    // update the user metadata with the new data
    (new BB_User_Manager)->updateUserData(userId: $user->ID, data: $userDetails);
    $bbSessionManager->setTransient(role: 'bb_discord_role', data: $userDetails['subscription']['discord_group']);
}

add_filter(hook_name: 'wp_login', callback: 'bb_after_login', priority: 10, accepted_args: 2);
