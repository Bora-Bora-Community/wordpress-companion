<?php

use BB\API\BB_Api_Client;
use BB\enum\Setting;

function my_custom_password_change($user_id, $new_pass): void
{
    $user = wp_get_current_user();
    $boraId = carbon_get_user_meta($user->ID, Setting::BORA_USER_ID);
    // now report the new password to the bora bora api
    (new BB_Api_Client())->updateCustomerPassword(boraBoraId: $boraId, newPassword: $new_pass);
}

add_action('check_passwords', 'my_custom_password_change', 10, 2);
