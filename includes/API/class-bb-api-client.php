<?php

namespace BB\API;

use GuzzleHttp\Client;

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

    /**
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function loadDiscordRoles()
    {
        $response = $this->client->get('load_discord_roles');

        return json_decode($response->getBody()->getContents(), true);
    }
}
