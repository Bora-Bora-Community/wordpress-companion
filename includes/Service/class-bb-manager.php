<?php

namespace BB\Service;

use BB\API\BB_Api_Client;

class BB_Manager
{
    public function updateCommunityRoles(): void
    {
        ray((new BB_Api_Client())->loadDiscordRoles());

    }
}
