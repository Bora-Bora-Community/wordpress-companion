<?php

use BB\API\BB_Api_Client;
use BB\Service\BB_Session_Manager;
use BB\Service\BB_User_Manager;

function my_custom_password_change($user_id, $new_pass): void
{
    $user = wp_get_current_user();
    $boraId = carbon_get_user_meta($user->ID, 'bora_bora_id');
    // now report the new password to the bora bora api
    (new BB_Api_Client())->updateCustomerPassword(boraBoraId: $boraId, newPassword: $new_pass);
}

add_action('check_passwords', 'my_custom_password_change', 10, 2);
