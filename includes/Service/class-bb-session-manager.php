<?php

namespace BB\Service;

/**
 * @since 1.0.0
 */
class BB_Session_Manager
{
    protected string $bbSubscription = 'bb_subscription';

    /**
     * @return bool
     */
    public function checkTransientExistsAndIsValid(): bool
    {
        return (bool) get_transient($this->bbSubscription);
    }

    /**
     * @param  string  $subscriptionId
     *
     * @return bool
     */
    public function setTransient(string $subscriptionId): bool
    {
        return set_transient(
            transient : $this->bbSubscription,
            value     : $subscriptionId,
            expiration: BORA_BORA_SESSION_VALID_TIMEFRAME_IN_HOURS * 60 * 60
        );
    }

    /**
     * @param  string  $subscriptionId
     *
     * @return bool
     */
    public function updateTransient(string $subscriptionId): bool
    {
        if ($this->checkTransientExistsAndIsValid()) {
            $this->deleteTransient();
        }

        return $this->setTransient($subscriptionId);
    }

    /**
     * @return bool
     */
    public function deleteTransient(): bool
    {
        return delete_transient($this->bbSubscription);
    }
}
