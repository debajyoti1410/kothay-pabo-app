<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeTraits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:trait {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new trait class inside app/Traits (supports nested folders)';

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

        $baseDir = app_path('Traits');
        $fullDir = $subPath ? $baseDir . '/' . $subPath : $baseDir;

        $fileName = $className . '.php';
        $filePath = $fullDir . '/' . $fileName;

        // Namespace handle
        $namespace = 'App\\Traits' . ($subPath ? '\\' . str_replace('/', '\\', $subPath) : '');

        // Create directory if not exists
        if (!File::exists($fullDir)) {
            File::makeDirectory($fullDir, 0755, true);
        }

        // Prevent overwrite
        if (File::exists($filePath)) {
            $this->error("Trait {$fileName} already exists at {$fullDir}");
            return Command::FAILURE;
        }

        // Template content
        $content = "<?php

namespace {$namespace};

trait {$className}
{
    //
}
        ";

        File::put($filePath, $content);

        $this->info("Trait {$className} created successfully at {$filePath}");

        return Command::SUCCESS;
    }
}
