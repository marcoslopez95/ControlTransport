<?php

/**
 * Created by PhpStorm.
 * User: zippyttech
 * Date: 06/08/18
 * Time: 03:11 PM
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GeneratorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generator:crud {name} {package?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generador de modelo con Repositorio, controller y servicio';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $name = $this->argument('name');
        $package = $this->argument('package') ?? $name;

        $this->controller($name, $package);
        $this->service($name, $package);
        $this->repository($name, $package);
        $this->model($name, $package);

        $this->getBr($name);
        $separator = '\\';
        $name_controller = "\App\Http\Controllers\\" . $package . $separator . $name . "Controller::class";
        File::append(base_path('routes/api.php'), "Route::apiResource('" . Str::plural(strtolower($name)) . "', $name_controller);\n");
    }

    protected function model($name, $package)
    {
        $modelTemplate = str_replace(
            ['{{modelName}}', 'package'],
            [$name, $package],
            $this->getStub('Model')
        );

        file_put_contents((app()->basePath() . "/app/Models/{$name}.php"), $modelTemplate);
    }

    protected function controller($name, $package)
    {
        $controllerTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}',
                '{{package}}'
            ],
            [
                $name,
                strtolower(Str::plural($name)),
                strtolower($name),
                $package
            ],
            $this->getStub('Controller')
        );
        mkdir((app()->basePath() . "/app/Http/Controllers/{$package}"));

        file_put_contents((app()->basePath() . "/app/Http/Controllers/{$package}/{$name}Controller.php"), $controllerTemplate);
    }

    protected function service($name, $package)
    {
        $controllerTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}',
                '{{package}}'
            ],
            [
                $name,
                strtolower(Str::plural($name)),
                strtolower($name),
                $package
            ],
            $this->getStub('Service')
        );
        mkdir((app()->basePath() . "/app/Services/{$package}"));

        file_put_contents((app()->basePath() . "/app/Services/{$package}/{$name}Service.php"), $controllerTemplate);
    }

    protected function repository($name, $package)
    {
        $controllerTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}',
                '{{package}}'
            ],
            [
                $name,
                strtolower(Str::plural($name)),
                strtolower($name),
                $package
            ],
            $this->getStub('Repository')
        );
        mkdir((app()->basePath() . "/app/Repositories/" . $package));
        file_put_contents((app()->basePath() . "/app/Repositories/{$package}/{$name}Repository.php"), $controllerTemplate);
    }

    protected function getStub($type)
    {
        // echo resource_path("Generator/stubs/$type.stub");
        return file_get_contents(resource_path("Generator/stubs/$type.stub"));
    }

    protected function getBr($name)
    {
        File::append(base_path('routes/api.php'), " \n");
        File::append(base_path('routes/api.php'), "/** routes para ${name} **/ \n");
        File::append(base_path('routes/api.php'), " \n");
    }
}
