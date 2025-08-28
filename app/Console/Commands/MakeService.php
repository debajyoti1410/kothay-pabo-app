<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service class inside app/Services (supports nested folders)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');

        // Support nested folders: User/AuthService → ['User', 'AuthService']
        $parts = explode('/', $name);
        $className = array_pop($parts);
        $subPath = implode('/', $parts);

        $baseDir = app_path('Services');
        $fullDir = $subPath ? $baseDir . '/' . $subPath : $baseDir;

        $fileName = $className . '.php';
        $filePath = $fullDir . '/' . $fileName;

        // Namespace handle
        $namespace = 'App\\Services' . ($subPath ? '\\' . str_replace('/', '\\', $subPath) : '');

        // Create directory if not exists
        if (!File::exists($fullDir)) {
            File::makeDirectory($fullDir, 0755, true);
        }

        // Prevent overwrite
        if (File::exists($filePath)) {
            $this->error("Service {$fileName} already exists at {$fullDir}");
            return Command::FAILURE;
        }

        // Template content
        $content = "<?php

namespace {$namespace};

class {$className}
{
    //
}
        ";

        File::put($filePath, $content);

        $this->info("Service {$className} created successfully at {$filePath}");

        return Command::SUCCESS;
    }
}
