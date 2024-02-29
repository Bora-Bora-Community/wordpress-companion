<?php

use BB\API\BB_Api_Client;
use BB\Service\BB_User_Manager;

add_action('wp', 'execute_on_load_page_hook_event');

function execute_on_load_page_hook_event()
{
    $accessValid = carbon_get_post_meta(get_the_ID(), 'bora_available_for_groups');

    if ($accessValid == []) {
        ray('no restrictions');

        return;
    }
}

function bb_after_login($user_login, $user)
{
    $bbClient = new BB_Api_Client();
    $boraBoraId = carbon_get_user_meta($user->ID, 'bora_bora_id');

    if ($boraBoraId == []) {
        ray('user not yet registered with bora bora. abort');

        return [];
    }
    $userDetails = $bbClient->loadUserDetails(boraBoraId: $boraBoraId);

    // update the user meta data with the new data
    (new BB_User_Manager)->updateUserData(userId: $user->ID, data: $userDetails);
    ray(['user data per api' => $userDetails]);
}

add_filter('wp_login', 'bb_after_login', 10, 2);
