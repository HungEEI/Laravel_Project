<?php
namespace Modules;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\User\src\Repositories\UserRepository;
use Modules\Video\src\Repositories\VideoRepository;
use Modules\Courses\src\Repositories\CoursesRepository;
use Modules\Lessons\src\Repositories\LessonsRepository;
use Modules\Document\src\Repositories\DocumentRepository;
use Modules\Students\src\Repositories\StudentsRepository;
use Modules\Teachers\src\Repositories\TeachersRepository;
use Modules\User\src\Repositories\UserRepositoryInterface;
use Modules\Video\src\Repositories\VideoRepositoryInterface;
use Modules\Categories\src\Repositories\CategoriesRepository;
use Modules\Courses\src\Repositories\CoursesRepositoryInterface;
use Modules\Lessons\src\Repositories\LessonsRepositoryInterface;
use Modules\Document\src\Repositories\DocumentRepositoryInterface;
use Modules\Students\src\Repositories\StudentsRepositoryInterface;
use Modules\Teachers\src\Repositories\TeachersRepositoryInterface;
use Modules\Categories\src\Repositories\CategoriesRepositoryInterface;

class ModuleServiceProvider extends ServiceProvider {

    private $middlewares = [
       
    ];

    public function bindingRepository() {

        // User Repository
        $this->app->singleton(
            UserRepositoryInterface::class,
            UserRepository::class
        );

        // Categories Repository
        $this->app->singleton(
            CategoriesRepositoryInterface::class,
            CategoriesRepository::class
        );

        // Courses Repository
        $this->app->singleton(
            CoursesRepositoryInterface::class,
            CoursesRepository::class
        );

        // Teachers Repository
        $this->app->singleton(
            TeachersRepositoryInterface::class,
            TeachersRepository::class
        );

        // Video Repository
        $this->app->singleton(
            VideoRepositoryInterface::class,
            VideoRepository::class
        );

        // Document Repository
        $this->app->singleton(
            DocumentRepositoryInterface::class,
            DocumentRepository::class
        );

        // Lessons Repository
        $this->app->singleton(
            LessonsRepositoryInterface::class,
            LessonsRepository::class
        );

        // Students Repository
        $this->app->singleton(
            StudentsRepositoryInterface::class,
            StudentsRepository::class
        );
        
    }

    private $commands = [
        
    ];

    public function boot() {
        $modules = $this->getModules();
        if (!empty($modules)) {
            foreach ($modules as $module) {
                $this->registerModule($module);
            }
        }

        Paginator::useBootstrapFive();
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

        // Repository
        $this->bindingRepository();
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
        Route::group(['namespace' => "Modules\\{$module}\src\Http\Controllers", 'middleware' => 'web'],function () use ($modulePath) {
            if (File::exists($modulePath . 'routes/web.php')) {
                $this->loadRoutesFrom($modulePath . "routes/web.php");
            }
        });

        Route::group(['namespace' => "Modules\\{$module}\src\Http\Controllers", 'middleware' => 'api', 'prefix' => 'api'],function () use ($modulePath) {
            if (File::exists($modulePath . 'routes/api.php')) {
                $this->loadRoutesFrom($modulePath . "routes/api.php");
            }
        });


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
