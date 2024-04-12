<?php

namespace BB\Service;

use BB\API\BB_Api_Client;
use BB\enum\Setting;

class BB_Manager
{
    protected BB_Api_Client $apiClient;

    public function __construct()
    {
        $this->apiClient = new BB_Api_Client();
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
        $roles = $this->apiClient->loadDiscordRoles();

        if (count($roles) === 0) {
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
