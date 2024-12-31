<?php

namespace Webkul\Security;

use Spatie\LaravelPackageTools\Package as BasePackage;

class Package extends BasePackage
{
    public bool $runsSettings = false;

    public array $settingFileNames = [];

    public function runsSettings(bool $runsSettings = true): static
    {
        $this->runsSettings = $runsSettings;

        return $this;
    }

    public function hasSetting(string $settingFileName): static
    {
        $this->settingFileNames[] = $settingFileName;

        return $this;
    }

    public function hasSettings(...$settingFileNames): static
    {
        $this->settingFileNames = array_merge(
            $this->settingFileNames,
            collect($settingFileNames)->flatten()->toArray()
        );

        return $this;
    }
}
