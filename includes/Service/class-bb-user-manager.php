<?php

namespace BB\Service;

class BB_User_Manager
{
    public function updateUserData(int $userId, array $data): void
    {
        carbon_set_user_meta($userId, 'bora_bora_id', $data['user']['id']);
        carbon_set_user_meta($userId, 'bora_bora_name', $data['user']['name']);
        carbon_set_user_meta($userId, 'bora_bora_email', $data['user']['email']);
        carbon_set_user_meta($userId, 'bora_bora_locale', $data['user']['locale']);
        carbon_set_user_meta($userId, 'bora_bora_discord_id', $data['user']['discord_user_id']);
        carbon_set_user_meta($userId, 'bora_bora_discord_username', $data['user']['discord_user_name']);
    }
}
