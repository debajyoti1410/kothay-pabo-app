<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeInterface extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:interface {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new interface inside app/Contracts';

    /**
     * Execute the console command.
     */
    public function handle() {
        $name = $this->argument('name');

        // Handle subfolder, like: Services/LeadService → ['Services', 'LeadService']
        $parts = explode('/', $name);
        $interfaceName = array_pop($parts); // last part is interface name
        $subPath = implode('/', $parts);

        $baseDir = app_path('Contracts');
        $fullDir = $subPath ? $baseDir . '/' . $subPath : $baseDir;

        $fileName = $interfaceName . '.php';
        $filePath = $fullDir . '/' . $fileName;

        // Namespace handle
        $namespace = 'App\\Contracts' . ($subPath ? '\\' . str_replace('/', '\\', $subPath) : '');

        // Create directory if not exists
        if (!File::exists($fullDir)) {
            File::makeDirectory($fullDir, 0755, true);
        }

        // Prevent overwrite
        if (File::exists($filePath)) {
            $this->error("Interface {$fileName} already exists at {$fullDir}");
            return Command::FAILURE;
        }

        // Template
        $content = "<?php

namespace {$namespace};

interface {$interfaceName}
{
    //
}
        ";

        File::put($filePath, $content);

        $this->info("Interface {$interfaceName} created at {$filePath}");
        return Command::SUCCESS;
    }
}
