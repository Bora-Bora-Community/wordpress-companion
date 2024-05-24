<?php

namespace BB\Service;

/**
 * Manages user sessions using WordPress transients.
 *
 * @since 1.0.0
 */
class BB_Session_Manager
{
    protected string $session_key = 'bb_discord_session';

    /**
     * Retrieves the user session based on user ID.
     *
     * @param  int  $userId  The user ID.
     *
     * @return array|bool The user session data or false if not found or expired.
     */
    public function getUserSession(int $userId)
    {
        $sessionData = get_user_meta($userId, $this->session_key, true);

        if (!$sessionData) {
            return false;
        }

        $currentTimestamp = time();
        $expirationTimestamp = $sessionData['timestamp'] + (BORA_BORA_SESSION_VALID_TIMEFRAME_IN_HOURS * 3600);

        if ($currentTimestamp > $expirationTimestamp) {
            return false; // Session expired
        }

        return $sessionData;
    }

    /**
     * Sets the user session with a timestamp.
     *
     * @param  int  $userId  The user ID.
     * @param  string  $role  The role data.
     *
     * @return bool True if the session was set successfully, false otherwise.
     */
    public function setUserSession(int $userId, string $role): bool
    {
        $sessionData = [
            'role'      => intval($role),
            'timestamp' => time(),
        ];

        return update_user_meta($userId, $this->session_key, $sessionData);
    }

    /**
     * Deletes the user session based on user ID.
     *
     * @param  int  $userId  The user ID.
     *
     * @return bool True if the session was deleted successfully, false otherwise.
     */
    public function deleteUserSession(int $userId): bool
    {
        return delete_user_meta($userId, $this->session_key);
    }
}
