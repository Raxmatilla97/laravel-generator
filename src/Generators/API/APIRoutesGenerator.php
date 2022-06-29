<?php

namespace InfyOm\Generator\Generators\API;

use Illuminate\Support\Str;
use InfyOm\Generator\Common\GeneratorConfig;
use InfyOm\Generator\Generators\BaseGenerator;

class APIRoutesGenerator extends BaseGenerator
{
    private GeneratorConfig $config;

    private string $path;

    private string $routeContents;

    private string $routesTemplate;

    public function __construct(GeneratorConfig $config)
    {
        $this->config = $config;
        $this->path = $this->config->paths->apiRoutes;

        $this->routeContents = file_get_contents($this->path);

        if (!empty($this->config->prefixes->route)) {
            $routesTemplate = get_template('api.routes.prefix_routes', 'laravel-generator');
        } else {
            $routesTemplate = get_template('api.routes.routes', 'laravel-generator');
        }

        $this->routesTemplate = fill_template($this->config->dynamicVars, $routesTemplate);
    }

    /**
     * Generate API Routes.
     *
     * @return void
     */
    public function generate()
    {
        $this->routeContents .= "\n\n".$this->routesTemplate;
        $existingRouteContents = file_get_contents($this->path);
        if (Str::contains($existingRouteContents, "Route::resource('".$this->config->modelNames->dashedPlural."',")) {
            $this->config->commandInfo('Menu '.$this->config->modelNames->dashedPlural.'is already exists, Skipping Adjustment.');

            return;
        }

        file_put_contents($this->path, $this->routeContents);

        $this->config->commandComment("\n".$this->config->modelNames->dashedPlural.' api routes added.');
    }

    /**
     * Remove API Routes.
     *
     * @return void
     */
    public function rollback()
    {
        if (Str::contains($this->routeContents, $this->routesTemplate)) {
            $this->routeContents = str_replace($this->routesTemplate, '', $this->routeContents);
            file_put_contents($this->path, $this->routeContents);
            $this->config->commandComment('api routes deleted');
        }
    }
}
