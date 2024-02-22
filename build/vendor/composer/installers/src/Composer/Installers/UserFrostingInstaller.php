<?php

namespace _PhpScoper9a3678ae6a12\Composer\Installers;

/** @internal */
class UserFrostingInstaller extends BaseInstaller
{
    /** @var array<string, string> */
    protected $locations = array('sprinkle' => 'app/sprinkles/{$name}/');
}
