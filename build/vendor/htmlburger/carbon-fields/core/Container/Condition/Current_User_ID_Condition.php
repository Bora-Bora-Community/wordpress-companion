<?php

namespace _PhpScoper9a3678ae6a12\Carbon_Fields\Container\Condition;

/**
 * Check if the currently logged in user has a specific id
 * @internal
 */
class Current_User_ID_Condition extends Condition
{
    /**
     * Check if the condition is fulfilled
     *
     * @param  array $environment
     * @return bool
     */
    public function is_fulfilled($environment)
    {
        $user_id = get_current_user_id();
        return $this->compare($user_id, $this->get_comparison_operator(), $this->get_value());
    }
}
