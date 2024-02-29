<?php

namespace BB\Service;

use WP_Application_Passwords;
use WP_User;

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
    
    public function createWPRole(string $roleName, string $roleDescription): void
    {
        // create a new role
        add_role(
            $roleName,
            $roleDescription,
            ['create_users' => true]
        );
    }
    
    public function deleteWPRole(string $roleName): void
    {
        remove_role($roleName);
    }
    
    public function WPRoleDoesNotExist(string $roleName): bool
    {
        return get_role($roleName) === null;
    }
    
    public function WPRoleExists(string $roleName): bool
    {
        return !$this->WPRoleDoesNotExist($roleName);
    }
    
    public function WPUserDoesNotExist(string $userName): bool
    {
        return get_user_by('login', $userName) === false;
    }
    
    public function createWPUser(
        string $userMgmtUserName,
        string $userMgmtUserEmail,
        string $userMgmtUserDescription
    ): WP_User {
        // create WP user
        $createUser = wp_create_user($userMgmtUserName, wp_generate_password(), $userMgmtUserEmail);
        $user = new WP_User($createUser);
        
        // set user first_name for WP overview list
        wp_update_user(['ID' => $user->ID, 'first_name' => $userMgmtUserDescription]);
        
        // create application password
        $passDetails = WP_Application_Passwords::create_new_application_password($user->ID,
            $args = ['name' => $userMgmtUserName]);
        
        // send application password to Bora Bora
        // ToDo: remove writing of application Password to lastname
        wp_update_user(['ID' => $user->ID, 'last_name' => $passDetails[0]]);
        
        return $user;
    }
    
    public function WPUserExists(string $userMgmtUserName): bool
    {
        return !$this->WPUserDoesNotExist($userMgmtUserName);
    }
    
    public function deleteWPUser(string $userName): void
    {
        // get user for user ID
        $user = get_user_by('login', $userName);
        
        if ($user->ID !== 0) {
            // only delete user if ID could be found by name
            wp_delete_user($user->ID);
        }
    }
}
