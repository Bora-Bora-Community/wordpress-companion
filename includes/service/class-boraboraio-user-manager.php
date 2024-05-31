<?php

namespace Boraboraio\Service;

use Boraboraio\API\BoraBoraio_Api_Client;
use Boraboraio\enum\Boraboraio_Setting;
use WP_Application_Passwords;
use WP_User;

class Boraboraio_User_Manager
{
    public function updateUserData(int $userId, array $data): void
    {
        // Sanitize user data
        $userIdSanitized = sanitize_text_field($data['user']['id']);
        $userNameSanitized = sanitize_text_field($data['user']['name']);
        $userEmailSanitized = sanitize_email($data['user']['email']);
        $userLocaleSanitized = sanitize_text_field($data['user']['locale']);
        $userDiscordIdSanitized = sanitize_text_field($data['user']['discord_user_id']);
        $userDiscordUsernameSanitized = sanitize_text_field($data['user']['discord_user_name']);

        // Sanitize referral details
        $referralLinkSanitized = esc_url_raw($data['referrals']['url'] ?? '');
        $referralCountSanitized = absint($data['referrals']['count'] ?? 0);

        // Sanitize billing portal URL
        $billingPortalUrlSanitized = esc_url_raw($data['billing_portal_url'] ?? '');

        // Update user meta with sanitized data
        carbon_set_user_meta($userId, Boraboraio_Setting::BORA_BORA_IO_USER_ID, $userIdSanitized);
        carbon_set_user_meta($userId, Boraboraio_Setting::BORA_BORA_IO_USER_NAME, $userNameSanitized);
        carbon_set_user_meta($userId, Boraboraio_Setting::BORA_BORA_IO_USER_EMAIL, $userEmailSanitized);
        carbon_set_user_meta($userId, Boraboraio_Setting::BORA_BORA_IO_USER_LOCALE, $userLocaleSanitized);
        carbon_set_user_meta($userId, Boraboraio_Setting::BORA_BORA_IO_USER_DISCORD_ID, $userDiscordIdSanitized);
        carbon_set_user_meta($userId, Boraboraio_Setting::BORA_BORA_IO_USER_DISCORD_USERNAME, $userDiscordUsernameSanitized);
        carbon_set_user_meta($userId, Boraboraio_Setting::BORA_BORA_IO_USER_REFERRAL_LINK, $referralLinkSanitized);
        carbon_set_user_meta($userId, Boraboraio_Setting::BORA_BORA_IO_USER_REFERRAL_COUNT, $referralCountSanitized);
        carbon_set_user_meta($userId, Boraboraio_Setting::BORA_BORA_IO_USER_BILLING_PORTAL_URL, $billingPortalUrlSanitized);
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
        (new BoraBoraio_Api_Client)->registerWordpressCompanionUser($userMgmtUserName, $password);

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
