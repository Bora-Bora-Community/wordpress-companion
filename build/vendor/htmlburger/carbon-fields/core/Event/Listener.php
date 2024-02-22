<?php

namespace _PhpScoper9a3678ae6a12\Carbon_Fields\Event;

/** @internal */
interface Listener
{
    /**
     * Get the listener's callable
     *
     * @return callable
     */
    public function get_callable();
    /**
     * Set the listener's callable
     *
     * @param callable $callable
     */
    public function set_callable($callable);
    /**
     * Get if the listener is valid
     *
     * @return boolean
     */
    public function is_valid();
    /**
     * Notify the listener that the event has been broadcasted
     *
     * @return mixed
     */
    public function notify();
}
