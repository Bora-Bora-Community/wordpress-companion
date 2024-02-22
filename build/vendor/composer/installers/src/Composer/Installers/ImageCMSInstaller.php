<?php

namespace _PhpScoper9a3678ae6a12\Composer\Installers;

/** @internal */
class ImageCMSInstaller extends BaseInstaller
{
    /** @var array<string, string> */
    protected $locations = array('template' => 'templates/{$name}/', 'module' => 'application/modules/{$name}/', 'library' => 'application/libraries/{$name}/');
}
