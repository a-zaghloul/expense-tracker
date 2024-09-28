<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class GenerateCrudViews extends Command
{
    protected $signature = 'make:crud-views {name}';
    protected $description = 'Generate CRUD views for a given resource';

    protected $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();
        $this->filesystem = $filesystem;
    }

    public function handle()
    {
        $name = $this->argument('name');
        $viewPath = resource_path("views/{$name}");

        if ($this->filesystem->exists($viewPath)) {
            $this->error("The views directory for {$name} already exists.");
            return;
        }

        $this->filesystem->makeDirectory($viewPath);

        $views = ['index', 'create', 'edit', 'show'];

        foreach ($views as $view) {
            $filePath = "{$viewPath}/{$view}.blade.php";
            $this->filesystem->put($filePath, "<!-- {$view} view for {$name} -->");
            $this->info("Created: {$filePath}");
        }

        $this->info("CRUD views for {$name} generated successfully.");
    }
}
