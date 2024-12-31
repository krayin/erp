<?php

namespace Webkul\Contact;

use Webkul\Support\Package;
use Webkul\Support\PackageServiceProvider;

class ContactServiceProvider extends PackageServiceProvider
{
    public static string $name = 'contacts';

    public function configureCustomPackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasTranslations();
    }

    public function packageBooted(): void
    {
        //
    }
}
