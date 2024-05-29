<?php

namespace BB\Service;

use BB\API\BoraBora_Api_Client;
use BB\enum\Setting;
use GuzzleHttp\Exception\GuzzleException;

class BoraBora_Manager
{
    protected BoraBora_Api_Client $apiClient;

    public function __construct()
    {
        $this->apiClient = new BoraBora_Api_Client();
    }

    /**
     * check if the API Key is valid
     * return true if the API Key is valid
     * return false if the API Key is not valid or stored
     */
    protected function checkApiKey(): bool
    {
        $bbApiKey = carbon_get_theme_option(Setting::API_KEY);
        if (!isset($bbApiKey) || $this->apiClient->apiKeyInvalid()) {
            return false;
        }

        return true;
    }

    public function updateCommunityRoles(): array
    {
        if (!$this->checkApiKey()) {
            return [];
        }
        try {
            $roles = $this->apiClient->loadDiscordRoles();
        } catch (\Exception|GuzzleException $e) {
            error_log('Error loading Discord roles with message: ' . $e->getMessage());
            return [];
        }

        if (count($roles) === 0) {
            error_log('No Discord roles found in the API response.');
            return [];
        }
        update_option('bb_community_roles', $roles);

        return $roles;
    }

    public function getCommunityRoles(): array
    {
        $roles = get_option('bb_community_roles', []);

        if ($roles == []) {
            $roles = $this->updateCommunityRoles();
        }

        return $roles;
    }
}
