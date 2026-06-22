<?php

namespace Boraboraio\Service;

use Boraboraio\API\Boraboraio_Api_Client;
use Boraboraio\enum\Boraboraio_Setting;
use WP_Application_Passwords;
use WP_User;

class Boraboraio_User_Manager
{
    /**
     * User meta flag marking an account as created and owned by this plugin,
     * so an existing same-named account is never escalated to the privileged
     * management role.
     */
    private const MANAGED_USER_META_KEY = 'bora_bora_companion_managed';

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

    /**
     * Ensure the Bora Bora management role and user exist locally.
     *
     * Performs no network calls, so it is safe to run on plugin activation
     * (before an API key has been entered).
     */
    public function ensureCompanionUser(): ?WP_User
    {
        try {
            if ($this->WPRoleDoesNotExist(BORABORAIO_USER_MGMT_ROLE_NAME)) {
                $this->createWPRole(BORABORAIO_USER_MGMT_ROLE_NAME, BORABORAIO_USER_MGMT_ROLE_DESC);
            }

            $userName = sanitize_text_field(BORABORAIO_USER_MGMT_USER_NAME);
            $user = get_user_by('login', $userName);

            if (!$user) {
                $userId = wp_create_user(
                    $userName,
                    wp_generate_password(),
                    sanitize_email(BORABORAIO_USER_MGMT_USER_EMAIL)
                );
                if (is_wp_error($userId)) {
                    throw new \Exception('User creation failed: '.$userId->get_error_message());
                }

                $user = new WP_User($userId);
                wp_update_user([
                    'ID'         => $user->ID,
                    'first_name' => sanitize_text_field(BORABORAIO_USER_MGMT_USER_DESC),
                ]);
                update_user_meta($user->ID, self::MANAGED_USER_META_KEY, 1);
            } elseif (!$this->isPluginManagedUser($user)) {
                // The reserved login already belongs to an account this plugin
                // did not create. Refuse to grant it the privileged management
                // role rather than silently escalating its capabilities.
                error_log('[Bora Bora Plugin] Refusing to assign the companion role: a user named "'.$userName.'" already exists but was not created by this plugin.');

                return null;
            }

            $user->set_role(BORABORAIO_USER_MGMT_ROLE_NAME);

            return $user;
        } catch (\Throwable $e) {
            error_log('[Bora Bora Plugin] Companion user setup error: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Determine whether an existing account is one this plugin owns.
     *
     * Accounts created by this plugin carry an ownership marker. Legacy users
     * created before the marker existed are recognised by the dedicated
     * management e-mail address the plugin assigns, and are migrated forward by
     * stamping the marker so the check is cheap on subsequent runs.
     */
    private function isPluginManagedUser(WP_User $user): bool
    {
        if (get_user_meta($user->ID, self::MANAGED_USER_META_KEY, true)) {
            return true;
        }

        if (is_email(BORABORAIO_USER_MGMT_USER_EMAIL)
            && strcasecmp($user->user_email, BORABORAIO_USER_MGMT_USER_EMAIL) === 0) {
            update_user_meta($user->ID, self::MANAGED_USER_META_KEY, 1);

            return true;
        }

        return false;
    }

    /**
     * Mint a fresh application password for the management user and register
     * the credentials with the Bora Bora backend.
     *
     * The new password is registered with the backend before any previous one
     * is revoked, so a backend failure never leaves an already connected site
     * disconnected — the old credential keeps working until the new one is
     * confirmed. On success every other plugin-issued password is revoked.
     *
     * Requires a valid API key to be stored — call this on settings save.
     */
    private function issueAndRegisterApplicationPassword(WP_User $user): bool
    {
        try {
            $passDetails = WP_Application_Passwords::create_new_application_password($user->ID, [
                'name' => $this->buildApplicationPasswordName(),
            ]);
            if (is_wp_error($passDetails)) {
                throw new \Exception('Application password creation failed: '.$passDetails->get_error_message());
            }

            $password = $passDetails[0];
            $newUuid = $passDetails[1]['uuid'];

            // Register the new credentials with the Bora Bora backend first.
            $registered = (new Boraboraio_Api_Client())->registerWordpressCompanionUser(
                BORABORAIO_USER_MGMT_USER_NAME,
                $password
            );
            if (!$registered) {
                // Roll back the unused password so it does not accumulate, and
                // leave the previously registered credential untouched.
                WP_Application_Passwords::delete_application_password($user->ID, $newUuid);

                return false;
            }

            // Registration succeeded — revoke every other plugin-issued
            // application password (rotated as well as legacy ones from versions
            // that predate this cleanup) so privileged credentials never pile up.
            $this->revokeStaleApplicationPasswords($user, $newUuid);

            return true;
        } catch (\Throwable $e) {
            error_log('[Bora Bora Plugin] Application password registration error: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Build the name this plugin gives every application password it issues.
     * The shared prefix is what lets {@see revokeStaleApplicationPasswords()}
     * recognise and clean up the credentials it owns.
     */
    private function buildApplicationPasswordName(): string
    {
        return BORABORAIO_USER_MGMT_USER_NAME.' '.gmdate('Y-m-d H:i:s');
    }

    /**
     * Revoke every plugin-issued application password on the companion user
     * except the one currently in use. Identifies owned credentials by the
     * name prefix, which also matches the bare-name form used by versions
     * before the stored-UUID era.
     */
    private function revokeStaleApplicationPasswords(WP_User $user, string $keepUuid): void
    {
        $passwords = WP_Application_Passwords::get_user_application_passwords($user->ID);
        foreach ($passwords as $passwordItem) {
            if ($passwordItem['uuid'] === $keepUuid) {
                continue;
            }
            if (strpos($passwordItem['name'], BORABORAIO_USER_MGMT_USER_NAME) !== 0) {
                continue;
            }

            $revoked = WP_Application_Passwords::delete_application_password($user->ID, $passwordItem['uuid']);
            if (is_wp_error($revoked)) {
                error_log('[Bora Bora Plugin] Failed to revoke stale application password '.$passwordItem['uuid'].': '.$revoked->get_error_message());
            }
        }
    }

    /**
     * Idempotently (re)establish the WordPress <-> Bora Bora credential link.
     *
     * Safe to run repeatedly; each run rotates the application password and
     * re-registers it with the backend. This is the single entry point for
     * connecting and for repairing a broken connection.
     */
    public function provisionCompanionConnection(): bool
    {
        $user = $this->ensureCompanionUser();
        if (!$user) {
            return false;
        }

        return $this->issueAndRegisterApplicationPassword($user);
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

    protected function isLocalEnvironment(): bool
    {
        $host = $_SERVER['HTTP_HOST'] ?? 'cli';

        return in_array($host, ['wp-bora-bora.test', 'bb_wp_demo.test'], true)
            || str_ends_with($host, '.local')
            || str_ends_with($host, '.test');
    }
}
