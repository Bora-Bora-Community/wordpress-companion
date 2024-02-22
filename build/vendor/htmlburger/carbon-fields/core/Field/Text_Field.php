<?php

namespace _PhpScoper9a3678ae6a12\Carbon_Fields\Field;

use _PhpScoper9a3678ae6a12\Carbon_Fields\Exception\Incorrect_Syntax_Exception;
/**
 * Text field class.
 * @internal
 */
class Text_Field extends Field
{
    /**
     * {@inheritDoc}
     */
    protected $allowed_attributes = array('list', 'max', 'maxLength', 'min', 'pattern', 'placeholder', 'readOnly', 'step', 'type', 'is', 'inputmode', 'autocomplete');
}
