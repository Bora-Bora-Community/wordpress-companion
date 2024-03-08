<?php

namespace BB\Service;

/**
 * @since 1.0.0
 */
class BB_Session_Manager
{
    public function getDiscordRoleId(): string|bool
    {
        return get_transient('bb_discord_role');
    }

    /**
     * @return bool
     */
    public function checkTransientExistsAndIsValid(): bool
    {
        return (bool) $this->getDiscordRoleId();
    }

    /**
     * @param  string  $data
     *
     * @return bool
     */
    public function setTransient(string $role, string $data): bool
    {
        return set_transient(
            transient : $role,
            value     : $data,
            expiration: BORA_BORA_SESSION_VALID_TIMEFRAME_IN_HOURS * 60 * 60
        );
    }

    /**
     * @param  string  $data
     *
     * @return bool
     */
    public function updateTransient(string $role, string $data): bool
    {
        if ($this->checkTransientExistsAndIsValid()) {
            $this->deleteTransient(role: $role);
        }

        return $this->setTransient(role: $role, data: $data);
    }

    /**
     * @return bool
     */
    public function deleteTransient(string $role): bool
    {
        return delete_transient($role);
    }
}
