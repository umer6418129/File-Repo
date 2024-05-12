<?php
// YourPackageServiceProvider.php

namespace umer2\repo;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

class RepoServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Listen for package installation event
        $this->app->booted(function () {
            // Generate migration file after package installation
            $this->generateMigration();
        });
    }

    protected function generateMigration()
    {
        // Check if the migration file already exists
        if (!$this->migrationExists('create_file_repos_table')) {
            // Generate migration file using Artisan command
            $migrationName = 'create_file_repos_table';
            $this->app['Illuminate\Contracts\Console\Kernel']->call('make:migration', [
                'name' => $migrationName,
                '--create' => 'file_repos', // Specify table name
                '--table' => true, // Indicates it's a table creation migration
            ]);

            // Find the path of the generated migration file
            $migrationPath = $this->getMigrationFilePath($migrationName);

            // Modify the generated migration file to include the desired columns
            $this->modifyMigrationFile($migrationPath);
        }
    }

    protected function migrationExists(string $migrationName)
    {
        // Find the path of the generated migration file
        $migrationFiles = File::files(database_path('migrations'));
        foreach ($migrationFiles as $file) {
            if (str_contains($file->getFilename(), $migrationName)) {
                return true;
            }
        }
        return false;
    }

    protected function getMigrationFilePath(string $migrationName)
    {
        // Find the path of the generated migration file
        $migrationFiles = File::files(database_path('migrations'));
        foreach ($migrationFiles as $file) {
            if (str_contains($file->getFilename(), $migrationName)) {
                return $file->getPathname();
            }
        }
        return null;
    }

    protected function modifyMigrationFile(string $migrationPath)
    {
        // Read the content of the migration file
        $content = file_get_contents($migrationPath);

        // Modify the migration content to include desired columns and their data types
        // Example: Add columns 'id', 'ref_id', 'ref_name', 'path'
        $content = str_replace(
            '$table->id();',
            "\$table->id();\n" .
                "            \$table->integer('ref_id');\n" .
                "            \$table->string('ref_name');\n" .
                "            \$table->text('path');\n",
            $content
        );

        // Update the table name
        $content = str_replace("Schema::create('1'", "Schema::create('file_repos'", $content);

        // Update the table name in the down() method
        $content = str_replace("Schema::dropIfExists('1')", "Schema::dropIfExists('file_repos')", $content);

        // Write the modified content back to the migration file
        file_put_contents($migrationPath, $content);
    }
}
