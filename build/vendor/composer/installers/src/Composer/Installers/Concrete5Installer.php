<?php

namespace _PhpScoper9a3678ae6a12\Composer\Installers;

/** @internal */
class Concrete5Installer extends BaseInstaller
{
    /** @var array<string, string> */
    protected $locations = array('core' => 'concrete/', 'block' => 'application/blocks/{$name}/', 'package' => 'packages/{$name}/', 'theme' => 'application/themes/{$name}/', 'update' => 'updates/{$name}/');
}
