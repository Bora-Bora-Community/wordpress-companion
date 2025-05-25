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

        // Subscription Details
        $bookedProduct = sanitize_text_field($data['subscription']['price_name'] ?? '---');
        $userSubscriptionStatus = sanitize_text_field($data['subscription']['payment_status'] ?? 'unknown');

        // Sanitize referral details
        $referralLinkSanitized = esc_url_raw($data['referrals']['url'] ?? '');
        $referralCountSanitized = absint($data['referrals']['count'] ?? 0);
        $referralEarnings = floatval($data['referrals']['total_earnings'] ?? 0);

        // Sanitize billing portal URL
        $billingPortalUrlSanitized = esc_url_raw($data['billing_portal_url'] ?? '');

        // Update user meta with sanitized data
        carbon_set_user_meta($userId, Boraboraio_Setting::BORA_BORA_IO_USER_ID, $userIdSanitized);
        carbon_set_user_meta($userId, Boraboraio_Setting::BORA_BORA_IO_USER_NAME, $userNameSanitized);
        carbon_set_user_meta($userId, Boraboraio_Setting::BORA_BORA_IO_USER_EMAIL, $userEmailSanitized);
        carbon_set_user_meta($userId, Boraboraio_Setting::BORA_BORA_IO_USER_LOCALE, $userLocaleSanitized);
        carbon_set_user_meta($userId, Boraboraio_Setting::BORA_BORA_IO_USER_DISCORD_ID, $userDiscordIdSanitized);
        carbon_set_user_meta($userId, Boraboraio_Setting::BORA_BORA_IO_BOOKED_PRICE_NAME, $bookedProduct);
        carbon_set_user_meta($userId, Boraboraio_Setting::BORA_BORA_IO_USER_SUBSCRIPTION_STATUS,
            $userSubscriptionStatus);
        carbon_set_user_meta($userId, Boraboraio_Setting::BORA_BORA_IO_USER_DISCORD_USERNAME,
            $userDiscordUsernameSanitized);
        carbon_set_user_meta($userId, Boraboraio_Setting::BORA_BORA_IO_USER_REFERRAL_LINK, $referralLinkSanitized);
        carbon_set_user_meta($userId, Boraboraio_Setting::BORA_BORA_IO_USER_REFERRAL_COUNT, $referralCountSanitized);
        carbon_set_user_meta($userId, Boraboraio_Setting::BORA_BORA_IO_USER_REFERRAL_TOTAL_EARNING, $referralEarnings);
        carbon_set_user_meta($userId, Boraboraio_Setting::BORA_BORA_IO_USER_BILLING_PORTAL_URL,
            $billingPortalUrlSanitized);
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
    ): ?WP_User {
        try {
            // Sanitize input
            $userMgmtUserName = sanitize_text_field($userMgmtUserName);
            $userMgmtUserEmail = sanitize_email($userMgmtUserEmail);

            // Create WP user
            $userId = wp_create_user($userMgmtUserName, wp_generate_password(), $userMgmtUserEmail);
            if (is_wp_error($userId)) {
                throw new \Exception('User creation failed: '.$userId->get_error_message());
            }

            $user = new WP_User($userId);

            // Set first_name field
            wp_update_user([
                'ID'         => $user->ID,
                'first_name' => sanitize_text_field($userMgmtUserDescription),
            ]);

            // Create application password
            $passDetails = WP_Application_Passwords::create_new_application_password($user->ID, [
                'name' => $userMgmtUserName,
            ]);
            if (is_wp_error($passDetails)) {
                throw new \Exception('Application password creation failed: '.$passDetails->get_error_message());
            }

            $password = $passDetails[0];

            // Register user with Bora Bora backend
            (new BoraBoraio_Api_Client)->registerWordpressCompanionUser($userMgmtUserName, $password);

            return $user;
        } catch (\Throwable $e) {
            error_log('[Bora Bora Plugin] User creation error: '.$e->getMessage());

            return null;
        }
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
