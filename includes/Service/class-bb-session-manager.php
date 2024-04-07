<?php

namespace BB\Service;

/**
 * @since 1.0.0
 */
class BB_Session_Manager
{
    public function getUserSession(int $userId = 0): string|bool
    {
        return get_transient('bb_discord_role_'.$userId);
    }

    /**
     * @param  int  $userId
     *
     * @return bool
     */
    public function checkUserSessionExists(int $userId = 0): bool
    {
        return (bool) $this->getUserSession(userId: $userId);
    }

    /**
     * @param  string  $role
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
     * @param  string  $role
     *
     * @return bool
     */
    public function deleteTransient(string $role): bool
    {
        return delete_transient($role);
    }

    public function deleteUserSession(int $userId): bool
    {
        return delete_transient('bb_discord_role_'.$userId);
    }
}
