<?php

namespace _PhpScoper9a3678ae6a12\Composer\Installers;

/**
 * An installer to handle MODX specifics when installing packages.
 * @internal
 */
class ModxInstaller extends BaseInstaller
{
    /** @var array<string, string> */
    protected $locations = array('extra' => 'core/packages/{$name}/');
}
