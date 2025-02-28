<?php

namespace App\Http\Controllers\Api\Core;

use App\Http\Controllers\Api\ApiCrudController;
use App\Http\Crud\Core\DemoCrudService;
use App\Models\Demo;
use Illuminate\Routing\Router;

class DemoCrudController extends ApiCrudController {
    public function __construct(DemoCrudService $crudService) {
        parent::__construct($crudService);
    }

    public function routes(Router $router) {
        parent::routes($router);

        $router->post('generate', [static::class, 'generate']);
        $router->post('clear', [static::class, 'clear']);
    }

    public function generate() {
        Demo::factory(1000)->create();
    }

    public function clear() {
        Demo::truncate();
    }
}
