<?php
namespace Modules;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;
use Modules\User\src\Repositories\UserRepository;

class ModuleServiceProvider extends ServiceProvider {

    private $middlewares = [
       
    ];

    private $commands = [
        
    ];

    public function boot() {
        $modules = $this->getModules();
        if (!empty($modules)) {
            foreach ($modules as $module) {
                $this->registerModule($module);
            }
        }
    }

    public function register() {
        // Configs
        $modules = $this->getModules();
        if (!empty($modules)) {
            foreach ($modules as $module) {
                $this->registerConfig($module);
            }
        }

        // Middleware
        $this->registerMiddlewares();

        // Command
        $this->commands($this->commands);

        $this->app->singleton(
            UserRepository::class
        );
    }

    // Lấy danh sách module
    private function getModules() {
        $directories = array_map('basename', File::directories(__DIR__));
        return $directories;
    }

    // Đăng ký module
    private function registerModule($module) {
        $modulePath = __DIR__ . "/{$module}/";

        // Khai báo route 
        if (File::exists($modulePath . 'routes/routes.php')) {
            $this->loadRoutesFrom($modulePath . "routes/routes.php");
        }

        // Khai báo migration 
        if (File::exists($modulePath . 'migrations')) {
            $this->loadMigrationsFrom($modulePath . "migrations");
        }

        // Khai báo languages
        if (File::exists($modulePath . 'resources/lang')) {
            $this->loadTranslationsFrom($modulePath . "resources/lang", strtolower($module));
            $this->loadJsonTranslationsFrom($modulePath . "resources/lang");
        }

        // Khai báo view
        if (File::exists($modulePath . 'resources/views')) {
            $this->loadViewsFrom($modulePath . "resources/views", strtolower($module));
        }

        // Khai báo helper
        if (File::exists($modulePath . 'helpers')) {
            $helperList = File::allFiles($modulePath . 'helpers');
            if (!empty($helperList)) {
                foreach ($helperList as $helper) {
                    $file = $helper->getPathname();
                    require $file;
                }
            }
        }
    }

    // Đăng ký config
    private function registerConfig($module) {
        $configPath = __DIR__ . '/' . $module . '/configs';
        if (File::exists($configPath)) {
            $configFiles = array_map('basename', File::allFiles($configPath));

            foreach ($configFiles as $config) {
                $alias = basename($config, '.php');
                $this->mergeConfigFrom($configPath . '/' . $config, $alias);
            }
        }
    }

    // Đăng ký middlewares
    private function registerMiddlewares() {
        if (!empty($this->middlewares)) {
            foreach ($this->middlewares as $key => $middleware) {
                $this->app['router']->pushMiddlewareToGroup($key, $middleware);
            }
        }
    }
}
