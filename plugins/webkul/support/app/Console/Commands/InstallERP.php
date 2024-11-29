<?php

namespace Webkul\Support\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class InstallERP extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'erp:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the ERP system with Filament and Filament Shield';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Starting ERP System Installation...');

        $this->runMigrations();

        $this->seedDatabase();

        $this->generatePermissions();

        $this->createSuperAdmin();

        $this->info('🎉 ERP System installation completed successfully!');
    }

    /**
     * Run database migrations.
     */
    protected function runMigrations(): void
    {
        $this->info('⚙️ Running database migrations...');

        Artisan::call('migrate', [], $this->getOutput());

        $this->info('✅ Migrations completed successfully.');
    }

    /**
     * Seed the database.
     */
    protected function seedDatabase(): void
    {
        $this->info('🌱 Seeding the database...');

        Artisan::call('db:seed', [], $this->getOutput());

        $this->info('✅ Database seeding completed.');
    }

    /**
     * Generate permissions without creating policies.
     */
    protected function generatePermissions()
    {
        $this->info('🛡️ Generating permissions...');

        Artisan::call('shield:generate', [
            '--all' => true,
            '--option' => 'permissions',
        ], $this->getOutput());

        $this->info('✅ Permissions generated successfully.');
    }

    /**
     * Create and assign a Super Admin user.
     */
    protected function createSuperAdmin()
    {
        $this->info('👤 Creating a Super Admin user...');

        Artisan::call('shield:super-admin', [], $this->getOutput());

        $this->info('✅ Super Admin created and assigned successfully.');
    }
}
