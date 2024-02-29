<?php

namespace BB\API;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * API Client for Bora Bora
 * @package API
 * @since 1.0.0
 * @url https://docs.guzzlephp.org/en/stable/
 */
class BB_Api_Client
{
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => BORA_BORA_API_BASE_URL,
            'headers'  => [
                'Accept'  => 'application/json',
                'api-key' => carbon_get_theme_option('bora_api_key') ?? 'n/a',
            ],
        ]);
    }

    public function apiKeyValid(): bool
    {
        try {
            $this->client->get('check_api_key');

            return true;
        } catch (GuzzleException) {
            return false;
        }
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
        try {
            $response = $this->client->get('load_discord_roles');
        } catch (GuzzleException) {
            return [];
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    public function loadUserDetails(string $boraBoraId): array
    {
        try {
            $data = $this->client->post('load_user_details', [
                'form_params' => [
                    'user_id' => $boraBoraId,
                ],
            ]);
        } catch(\Exception) {
            return [];
        }

        return json_decode($data->getBody()->getContents(), true);
    }
}
