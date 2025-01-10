<?php

namespace Webkul\Recruitment;

use Webkul\Support\Package;
use Webkul\Support\PackageServiceProvider;
use Spatie\LaravelPackageTools\Commands\InstallCommand;

class RecruitmentServiceProvider extends PackageServiceProvider
{
    public static string $name = 'recruitments';

    public static string $viewNamespace = 'recruitments';

    public function configureCustomPackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasViews()
            ->hasTranslations()
            ->hasMigrations([
                '2025_01_06_133002_create_recruitments_stages_table',
                '2025_01_07_053021_create_recruitments_stages_jobs_table',
                '2025_01_09_071817_create_recruitments_degrees_table',
                '2025_01_09_082748_create_recruitments_refuse_reasons_table',
                '2025_01_09_095909_create_recruitments_applicant_categories_table',
                '2025_01_09_111545_create_recruitments_utm_mediums_table',
                '2025_01_09_114324_create_recruitments_applicant_sources_table',
                '2025_01_09_125852_create_recruitments_candidates_table',
                '2025_01_10_045048_create_recruitments_candidate_applicant_categories_table',
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
                                '--class' => 'Webkul\\Recruitment\\Database\Seeders\\DatabaseSeeder',
                            ]);
                        }
                    })
                    ->askToStarRepoOnGitHub('krayin/recruitments');
            });
    }
}
