<?php

namespace _PhpScoper9a3678ae6a12\Carbon_Fields\Container\Fulfillable;

/** @internal */
interface Fulfillable
{
    /**
     * Check if the condition is fulfilled
     *
     * @param  array $environment
     * @return bool
     */
    public function is_fulfilled($environment);
}
