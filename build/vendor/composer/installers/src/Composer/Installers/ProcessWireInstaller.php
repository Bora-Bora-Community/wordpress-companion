<?php

namespace _PhpScoper9a3678ae6a12\Composer\Installers;

/** @internal */
class ProcessWireInstaller extends BaseInstaller
{
    /** @var array<string, string> */
    protected $locations = array('module' => 'site/modules/{$name}/');
    /**
     * Format package name to CamelCase
     */
    public function inflectPackageVars(array $vars) : array
    {
        $vars['name'] = \strtolower($this->pregReplace('/(?<=\\w)([A-Z])/', '_PhpScoper9a3678ae6a12\\_\\1', $vars['name']));
        $vars['name'] = \str_replace(array('-', '_'), ' ', $vars['name']);
        $vars['name'] = \str_replace(' ', '', \ucwords($vars['name']));
        return $vars;
    }
}