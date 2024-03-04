<?php

namespace BB\Service;

/**
 * @since 1.0.0
 */
class BB_Session_Manager
{
    /**
     * @return bool
     */
    public function checkTransientExistsAndIsValid(string $role): bool
    {
        return (bool) get_transient($role);
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
        if ($this->checkTransientExistsAndIsValid(role: $role)) {
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
