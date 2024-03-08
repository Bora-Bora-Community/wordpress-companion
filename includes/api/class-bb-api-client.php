<?php

namespace BB\API;

/**
 * API Client for Bora Bora
 * @package API
 * @since 1.0.0
 * @url https://docs.guzzlephp.org/en/stable/
 */
class BB_Api_Client
{
    public function apiKeyValid(): bool {
        $api_url = BORA_BORA_API_BASE_URL . 'check_api_key';
        $api_key = carbon_get_theme_option('bora_api_key') ?? 'n/a';

        $response = wp_remote_get($api_url, [
            'headers' => [
                'api-key' => $api_key,
            ],
            'timeout' => 45,
        ]);

        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) != 200) {
            // Wenn ein Fehler auftritt oder der Statuscode nicht 200 ist, wird false zurückgegeben
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
    public function loadDiscordRoles() {
        $api_url = BORA_BORA_API_BASE_URL . 'load_discord_roles';
        $api_key = carbon_get_theme_option('bora_api_key') ?? 'n/a';

        $response = wp_remote_get($api_url, [
            'headers' => [
                'Accept' => 'application/json',
                'api-key' => $api_key,
            ],
            'timeout' => 45, // Anpassen basierend auf den Bedürfnissen und Best Practices
        ]);

        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) != 200) {
            // Fehlerbehandlung, leeres Array zurückgeben, wenn die Anfrage fehlschlägt oder der Statuscode nicht 200 ist
            return [];
        }

        // Antwort erfolgreich erhalten, Antwortbody holen und JSON-dekodieren
        $body = wp_remote_retrieve_body($response);
        return json_decode($body, true);
    }


    public function loadUserDetails(string $boraBoraId): array {
        $api_url = BORA_BORA_API_BASE_URL . 'load_user_details';
        $api_key = carbon_get_theme_option('bora_api_key') ?? 'n/a';

        $response = wp_remote_post($api_url, [
            'body' => [
                'user_id' => $boraBoraId,
            ],
            'headers' => [
                'Accept' => 'application/json',
                'api-key' => $api_key,
            ],
            'timeout' => 45, // Anpassen basierend auf den Bedürfnissen und Best Practices
        ]);

        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) != 200) {
            // Fehlerbehandlung, leeres Array zurückgeben, wenn die Anfrage fehlschlägt oder der Statuscode nicht 200 ist
            return [];
        }

        // Antwort erfolgreich erhalten, Antwortbody holen und JSON-dekodieren
        $body = wp_remote_retrieve_body($response);
        return json_decode($body, true);
    }


    public function registerWordpressCompanionUser(string $username, string $password): bool
    {
        $api_url = BORA_BORA_API_BASE_URL . 'set_wp_application_user';
        $api_key = get_option('bora_api_key') ?? 'n/a';

        $response = wp_remote_post($api_url, [
            'body' => [
                'username' => $username,
                'password' => $password,
            ],
            'headers' => [
                'Accept' => 'application/json',
                'api-key' => $api_key,
            ],
            'timeout' => 45, // Setzen Sie ein angemessenes Timeout für die Anfrage
        ]);

        if (is_wp_error($response)) {
            // Im Fehlerfall können Sie zusätzliche Fehlerbehandlung hier hinzufügen
            return false;
        } else {
            // Sie können zusätzliche Prüfungen auf den Statuscode oder die Antwort hier durchführen, bevor Sie true zurückgeben
            return true;
        }
    }
}
