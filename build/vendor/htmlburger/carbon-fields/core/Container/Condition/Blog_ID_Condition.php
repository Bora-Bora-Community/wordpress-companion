<?php

namespace _PhpScoper9a3678ae6a12\Carbon_Fields\Container\Condition;

/**
 * Check if the current blog has a specific id
 * @internal
 */
class Blog_ID_Condition extends Condition
{
    /**
     * Check if the condition is fulfilled
     *
     * @param  array $environment
     * @return bool
     */
    public function is_fulfilled($environment)
    {
        $blog_id = get_current_blog_id();
        return $this->compare($blog_id, $this->get_comparison_operator(), $this->get_value());
    }
}
