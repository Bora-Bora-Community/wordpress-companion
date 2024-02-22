<?php

namespace _PhpScoper9a3678ae6a12\Carbon_Fields\Event;

/** @internal */
class SingleEventListener extends PersistentListener
{
    /**
     * Flag if the event has been called
     *
     * @var boolean
     */
    protected $called = \false;
    /**
     * {@inheritDoc}
     */
    public function is_valid()
    {
        return !$this->called;
    }
    /**
     * {@inheritDoc}
     */
    public function notify()
    {
        $this->called = \true;
        return \call_user_func_array(array($this, 'parent::notify'), \func_get_args());
    }
}
