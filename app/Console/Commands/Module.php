<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
class Module extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create module CLI';

    /**
     * Execute the console command.
     */
    public function handle()
    {   
        $name = $this->argument('name');

        if (File::exists(base_path('modules/'.$name))) {
            $this->error('Module already exists!');
        }else {
            File::makeDirectory(base_path('modules/'.$name), 755, true, true);

            //config
            $configFolder = base_path('modules/'.$name.'/configs');
            if (!File::exists($configFolder)) {
                File::makeDirectory($configFolder, 755, true, true);
            }

            //config
            $helperFolder = base_path('modules/'.$name.'/helpers');
            if (!File::exists($helperFolder)) {
                File::makeDirectory($helperFolder, 755, true, true);
            }

            //migrations
            $migrationFolder = base_path('modules/'.$name.'/migrations');
            if (!File::exists($migrationFolder)) {
                File::makeDirectory($migrationFolder, 755, true, true);
            }

            //resources
            $resourceFolder = base_path('modules/'.$name.'/resources');
            if (!File::exists($resourceFolder)) {
                File::makeDirectory($resourceFolder, 755, true, true);

                //lang 
                $langFolder = base_path('modules/'.$name.'/resources/lang');
                if (!File::exists($langFolder)) {
                    File::makeDirectory($langFolder, 755, true, true);
                }

                //views
                $viewsFolder = base_path('modules/'.$name.'/resources/views');
                if (!File::exists($viewsFolder)) {
                    File::makeDirectory($viewsFolder, 755, true, true);
                }
            }

            //roures
            $routesFolder = base_path('modules/'.$name.'/routes');
            if (!File::exists($routesFolder)) {
                File::makeDirectory($routesFolder, 755, true, true);

                // Tạo file routes.php
                $routesFile = base_path('modules/'.$name.'/routes/routes.php');

                if (!File::exists($routesFile)) {
                    File::put($routesFile, "<?php \nuse Illuminate\Support\Facades\Route;");
                }
            }

            //src 
            $srcFolder = base_path('modules/'.$name.'/src');
            if (!File::exists($srcFolder)) {
                File::makeDirectory($srcFolder, 755, true, true);

                //Commands
                $commandsFolder = base_path('modules/'.$name.'/src/Commands');
                if (!File::exists($commandsFolder)) {
                    File::makeDirectory($commandsFolder, 755, true, true);
                }

                //Http
                $httpFolder = base_path('modules/'.$name.'/src/Http');
                if (!File::exists($httpFolder)) {
                    File::makeDirectory($httpFolder, 755, true, true);

                    //Controller
                    $controllersFolder = base_path('modules/'.$name.'/src/Http/Controllers');
                    if (!File::exists($controllersFolder)) {
                        File::makeDirectory($controllersFolder, 755, true, true);
                    }

                    //Middlewares
                    $middlewaresFolder = base_path('modules/'.$name.'/src/Http/Middlewares');
                    if (!File::exists($middlewaresFolder)) {
                        File::makeDirectory($middlewaresFolder, 755, true, true);
                    }
                }

                //Models
                $modelsFolder = base_path('modules/'.$name.'/src/Models');
                if (!File::exists($modelsFolder)) {
                    File::makeDirectory($modelsFolder, 755, true, true);
                }

                // Repositories
                $repositoriesFolder = base_path('modules/'.$name.'/src/Repositories');
                if (!File::exists($repositoriesFolder)) {
                    File::makeDirectory($repositoriesFolder, 755, true, true);

                    // Module Repository
                    $moduleRepositoryFile = base_path('modules/'.$name.'/src/Repositories/'.$name.'Repository.php');
                    if (!File::exists($moduleRepositoryFile)) {

                        $moduleRepositoryFileContent = file_get_contents(app_path('Console/Commands/Templates/ModuleRepository.txt'));

                        $moduleRepositoryFileContent = str_replace('{module}', $name, $moduleRepositoryFileContent);

                        File::put($moduleRepositoryFile, $moduleRepositoryFileContent);
                    }

                    // Module Repository Interface
                    $moduleRepositoryInterfaceFile = base_path('modules/'.$name.'/src/Repositories/'.$name.'RepositoryInterface.php');
                    if (!File::exists($moduleRepositoryInterfaceFile)) {

                        $moduleRepositoryInterfaceFileContent = file_get_contents(app_path('Console/Commands/Templates/ModuleRepositoryInterface.txt'));

                        $moduleRepositoryInterfaceFileContent = str_replace('{module}', $name, $moduleRepositoryInterfaceFileContent);

                        File::put($moduleRepositoryInterfaceFile, $moduleRepositoryInterfaceFileContent);
                    }

                }

            }

            $this->info('Module created successfully!');
        }
     
    }
}
