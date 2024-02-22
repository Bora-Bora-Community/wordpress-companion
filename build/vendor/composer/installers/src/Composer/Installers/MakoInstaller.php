<?php

namespace _PhpScoper9a3678ae6a12\Composer\Installers;

/** @internal */
class MakoInstaller extends BaseInstaller
{
    /** @var array<string, string> */
    protected $locations = array('package' => 'app/packages/{$name}/');
}
