<?php

namespace BB\Service;

use BB\API\BoraBora_Api_Client;
use BB\enum\Setting;
use WP_Application_Passwords;
use WP_User;

class BoraBora_User_Manager
{
    public function updateUserData(int $userId, array $data): void
    {
        carbon_set_user_meta($userId, Setting::BORA_USER_ID, $data['user']['id']);
        carbon_set_user_meta($userId, Setting::BORA_USER_NAME, $data['user']['name']);
        carbon_set_user_meta($userId, Setting::BORA_USER_EMAIL, $data['user']['email']);
        carbon_set_user_meta($userId, Setting::BORA_USER_LOCALE, $data['user']['locale']);
        carbon_set_user_meta($userId, Setting::BORA_USER_DISCORD_ID, $data['user']['discord_user_id']);
        carbon_set_user_meta($userId, Setting::BORA_USER_DISCORD_USERNAME, $data['user']['discord_user_name']);

        // referral details
        carbon_set_user_meta($userId, Setting::BORA_USER_REFERRAL_LINK, $data['referrals']['url'] ?? '');
        carbon_set_user_meta($userId, Setting::BORA_USER_REFERRAL_COUNT, $data['referrals']['count'] ?? 0);

        // billing portal
        carbon_set_user_meta($userId, Setting::BORA_USER_BILLING_PORTAL_URL, $data['billing_portal_url'] ?? '');
    }

    public function createWPRole(string $roleName, string $roleDescription): void
    {
        // create a new role
        add_role(
            $roleName,
            $roleDescription,
            [
                'create_users' => true,
                'delete_users' => true,
                'edit_users'   => true,
                'list_users'   => true,
                'read'         => true,
                'remove_users' => true,
            ]
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
        $userMgmtUserName = sanitize_text_field($userMgmtUserName);
        $userMgmtUserEmail = sanitize_email($userMgmtUserEmail);
        $createUser = wp_create_user($userMgmtUserName, wp_generate_password(), $userMgmtUserEmail);
        $user = new WP_User($createUser);

        // set user first_name for WP overview list
        wp_update_user(['ID' => $user->ID, 'first_name' => $userMgmtUserDescription]);

        // create application password
        $passDetails = WP_Application_Passwords::create_new_application_password($user->ID,
            ['name' => $userMgmtUserName]);

        // send application password to Bora Bora
        $password = $passDetails[0];
        (new BoraBora_Api_Client)->registerWordpressCompanionUser($userMgmtUserName, $password);

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
