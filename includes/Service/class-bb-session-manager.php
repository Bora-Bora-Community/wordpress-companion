<?php

namespace BB\Service;

class BB_Session_Manager
{
    protected string $bbSubscription = 'bb_subscription';

    public function checkCookieExistsAndIsValid(): bool
    {
        if (!isset($_COOKIE[$this->bbSubscription])) {
            return false;
        }

        return true;
    }

    public function setCookie(string $subscriptionId): bool
    {
        return setcookie(
            $this->bbSubscription,
            $subscriptionId,
            time() + BORA_BORA_SESSION_VALID_TIMEFRAME_IN_HOURS * 60 * 60
        );
    }

    public function deleteCookie(): void
    {
        if (isset($_COOKIE[$this->bbSubscription])) {
            unset($_COOKIE[$this->bbSubscription]);
        }
    }
}
