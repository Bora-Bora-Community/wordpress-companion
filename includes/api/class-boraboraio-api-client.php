<?php

namespace Boraboraio\API;

use Boraboraio\enum\Boraboraio_Setting;

/**
 * API Client for Bora Bora
 * @package API
 * @since 1.0.0
 * @url https://docs.guzzlephp.org/en/stable/
 */
class Boraboraio_Api_Client
{
    public function __construct()
    {
        // Deaktivieren Sie die SSL-Überprüfung, wenn Sie mit einer lokalen Entwicklungsumgebung arbeiten
        // prüfe vorher, ob wordpress in der dev entwicklungs umgebung ist
        if (defined('BORA_BORA_WP_ENV') && BORABORAIO_WP_ENV === 'dev') {
            add_filter('http_request_args', function ($args, $url) {
                $args['sslverify'] = false;

                return $args;
            }, 10, 2);
        }
    }

    public function apiKeyValid(): bool
    {
        $api_url = BORABORAIO_API_BASE_URL.'check_api_key';
        $api_key = sanitize_text_field(get_option(Boraboraio_Setting::BORA_BORA_IO_API_KEY)) ?? 'n/a';

        $response = wp_remote_get($api_url, [
            'headers' => [
                'api-key' => $api_key,
            ],
            'timeout' => 45,
        ]);

        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) != 200) {
            return false;
        }

        return true;
    }

    public function apiKeyInvalid(): bool
    {
        return !$this->apiKeyValid();
    }

    /**
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function loadDiscordRoles()
    {
        $api_url = BORABORAIO_API_BASE_URL.'load_discord_roles';
        $api_key = sanitize_text_field(get_option(Boraboraio_Setting::BORA_BORA_IO_API_KEY)) ?? 'n/a';

        $response = wp_remote_get($api_url, [
            'headers' => [
                'Accept'  => 'application/json',
                'api-key' => $api_key,
            ],
            'timeout' => 45,
        ]);

        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) != 200) {
            return [];
        }

        $body = wp_remote_retrieve_body($response);

        return json_decode($body, true);
    }

    public function loadUserDetails(string $boraBoraId): array
    {
        $api_url = BORABORAIO_API_BASE_URL.'load_user_details';
        $api_key = sanitize_text_field(get_option(Boraboraio_Setting::BORA_BORA_IO_API_KEY)) ?? 'n/a';

        $response = wp_remote_post($api_url, [
            'body'    => [
                'user_id' => $boraBoraId,
            ],
            'headers' => [
                'Accept'  => 'application/json',
                'api-key' => $api_key,
            ],
            'timeout' => 45,
        ]);

        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) != 200) {
            return [];
        }

        $body = wp_remote_retrieve_body($response);

        return json_decode($body, true);
    }

    public function loadUserDetailsByMail(string $userEmail): array
    {
        $api_url = BORABORAIO_API_BASE_URL.'load_user_details_by_mail';
        $api_key = sanitize_text_field(get_option(Boraboraio_Setting::BORA_BORA_IO_API_KEY)) ?? 'n/a';

        $response = wp_remote_post($api_url, [
            'body'    => [
                'email' => $userEmail,
            ],
            'headers' => [
                'Accept'  => 'application/json',
                'api-key' => $api_key,
            ],
            'timeout' => 45,
        ]);

        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) != 200) {
            return [];
        }

        $body = wp_remote_retrieve_body($response);

        return json_decode($body, true);
    }

    public function updateCustomerPassword(string $boraBoraId, string $newPassword): void
    {
        $api_url = BORABORAIO_API_BASE_URL.'update_customer_user_password';
        $api_key = sanitize_text_field(get_option(Boraboraio_Setting::BORA_BORA_IO_API_KEY)) ?? 'n/a';

        wp_remote_post($api_url, [
            'body'    => [
                'bora_bora_id' => $boraBoraId,
                'new_password' => $newPassword,
            ],
            'headers' => [
                'Accept'  => 'application/json',
                'api-key' => $api_key,
            ],
            'timeout' => 45,
        ]);
    }

    public function fetchCustomerStripeBalance(string $boraBoraId): float
    {
        $api_url = BORABORAIO_API_BASE_URL.'load_user_stripe_balance';
        $api_key = sanitize_text_field(get_option(Boraboraio_Setting::BORA_BORA_IO_API_KEY)) ?? 'n/a';

        $response = wp_remote_post($api_url, [
            'body'    => [
                'user_id' => $boraBoraId,
            ],
            'headers' => [
                'Accept'  => 'application/json',
                'api-key' => $api_key,
            ],
            'timeout' => 45,
        ]);

        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) != 200) {
            return 0;
        }
        $body = wp_remote_retrieve_body($response);

        return json_decode($body, true)['balance'];
    }

    public function registerWordpressCompanionUser(string $username, string $password): bool
    {
        $api_url = BORABORAIO_API_BASE_URL.'set_wp_application_user';
        $api_key = sanitize_text_field(get_option(Boraboraio_Setting::BORA_BORA_IO_API_KEY)) ?? 'n/a';

        $response = wp_remote_post($api_url, [
            'body'    => [
                'username' => $username,
                'password' => $password,
            ],
            'headers' => [
                'Accept'  => 'application/json',
                'api-key' => $api_key,
            ],
            'timeout' => 45,
        ]);

        if (is_wp_error($response)) {
            return false;
        } else {
            return true;
        }
    }

    public function publishWordpressUri(int $paymentSuccessPageId, int $paymentFailedPageId): bool
    {
        $api_url = BORABORAIO_API_BASE_URL.'register_wp_uri';
        $api_key = sanitize_text_field(get_option(Boraboraio_Setting::BORA_BORA_IO_API_KEY)) ?? 'n/a';

        $response = wp_remote_post($api_url, [
            'body'    => [
                'wordpress_url'         => get_site_url(),
                'wordpress_success_url' => get_permalink($paymentSuccessPageId),
                'wordpress_fail_url'    => get_permalink($paymentFailedPageId),
            ],
            'headers' => [
                'Accept'  => 'application/json',
                'api-key' => $api_key,
            ],
            'timeout' => 45,
        ]);

        if (is_wp_error($response)) {
            return false;
        } else {
            return true;
        }
    }
}
