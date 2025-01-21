<?php

namespace Webkul\TimeOff;

use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Webkul\Support\Package;
use Webkul\Support\PackageServiceProvider;

class TimeOffServiceProvider extends PackageServiceProvider
{
    public static string $name = 'time_off';

    public static string $viewNamespace = 'time_off';

    public function configureCustomPackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasViews()
            ->hasTranslations()
            ->hasMigrations([
                '2025_01_17_080712_create_time_off_leaves_table',
                '2025_01_20_055423_create_time_off_leave_types_table',
                '2025_01_20_080058_create_time_off_user_leave_types_table',
                '2025_01_20_130725_create_time_off_leave_mandatory_days_table',
                '2025_01_21_073921_create_time_off_leave_accrual_plans_table',
                '2025_01_21_085833_create_time_off_leave_accrual_levels_table',
            ])
            ->runsMigrations()
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->askToRunMigrations()
                    ->copyAndRegisterServiceProviderInApp()
                    ->endWith(function (InstallCommand $command) {
                        if ($command->confirm('Would you like to seed the data now?')) {
                            $command->comment('Seeding data...');

                            $command->call('db:seed', [
                                '--class' => 'Webkul\\TimeOff\\Database\Seeders\\DatabaseSeeder',
                            ]);
                        }
                    })
                    ->askToStarRepoOnGitHub('krayin/time-off');
            });
    }
}
