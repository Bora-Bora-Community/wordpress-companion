<?php

namespace Boraboraio\Service;

use Boraboraio\API\Boraboraio_Api_Client;
use Boraboraio\enum\Boraboraio_Setting;
use GuzzleHttp\Exception\GuzzleException;

class Boraboraio_Manager
{
    protected Boraboraio_Api_Client $apiClient;

    public function __construct()
    {
        $this->apiClient = new Boraboraio_Api_Client();
    }

    /**
     * check if the API Key is valid
     * return true if the API Key is valid
     * return false if the API Key is not valid or stored
     */
    protected function checkApiKey(): bool
    {
        $bbApiKey = carbon_get_theme_option(Boraboraio_Setting::BORA_BORA_IO_API_KEY);
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
        update_option('boraboraio_community_roles', $roles);

        return $roles;
    }

    public function getCommunityRoles(): array
    {
        $roles = get_option('boraboraio_community_roles', []);

        if ($roles == []) {
            $roles = $this->updateCommunityRoles();
        }

        return $roles;
    }
}
