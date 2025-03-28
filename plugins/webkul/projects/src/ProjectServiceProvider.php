<?php

namespace Webkul\Project;

use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Webkul\Support\Package;
use Webkul\Support\PackageServiceProvider;

class ProjectServiceProvider extends PackageServiceProvider
{
    public static string $name = 'projects';

    public static string $viewNamespace = 'projects';

    public function configureCustomPackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasViews()
            ->hasTranslations()
            ->hasMigrations([
                '2024_12_12_074920_create_projects_project_stages_table',
                '2024_12_12_074929_create_projects_projects_table',
                '2024_12_12_074930_create_projects_milestones_table',
                '2024_12_12_100227_create_projects_user_project_favorites_table',
                '2024_12_12_100230_create_projects_tags_table',
                '2024_12_12_100232_create_projects_project_tag_table',
                '2024_12_12_101340_create_projects_task_stages_table',
                '2024_12_12_101344_create_projects_tasks_table',
                '2024_12_12_101350_create_projects_task_users_table',
                '2024_12_12_101352_create_projects_task_tag_table',
                '2024_12_18_145142_add_columns_to_analytic_records_table',
            ])
            ->runsMigrations()
            ->hasSettings([
                '2024_12_16_094021_create_project_task_settings',
                '2024_12_16_094021_create_project_time_settings',
            ])
            ->runsSettings()
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->askToRunMigrations()
                    ->copyAndRegisterServiceProviderInApp()
                    ->endWith(function (InstallCommand $command) {
                        if ($command->confirm('Would you like to seed the data now?')) {
                            $command->comment('Seeding data...');

                            $command->call('db:seed', [
                                '--class' => 'Webkul\\Project\\Database\Seeders\\DatabaseSeeder',
                            ]);
                        }
                    })
                    ->askToStarRepoOnGitHub('krayin/projects');
            });
    }

    public function packageBooted(): void
    {
        //
    }
}
