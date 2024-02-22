<?php

namespace _PhpScoper9a3678ae6a12\Composer\Installers;

/** @internal */
class LithiumInstaller extends BaseInstaller
{
    /** @var array<string, string> */
    protected $locations = array('library' => 'libraries/{$name}/', 'source' => 'libraries/_source/{$name}/');
}
