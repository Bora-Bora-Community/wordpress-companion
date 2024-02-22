<?php

namespace _PhpScoper9a3678ae6a12\Composer\Installers;

/** @internal */
class RedaxoInstaller extends BaseInstaller
{
    /** @var array<string, string> */
    protected $locations = array('addon' => 'redaxo/include/addons/{$name}/', 'bestyle-plugin' => 'redaxo/include/addons/be_style/plugins/{$name}/');
}
