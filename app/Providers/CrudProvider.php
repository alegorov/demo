<?php

namespace App\Providers;

use App\Http\Crud\Service\CrudService;
use App\Http\Crud\Service\CrudServicesManager;
use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;

class CrudProvider extends ServiceProvider {

    public $singletons = [
        CrudServicesManager::class,
    ];

    public function register() {
        $this->registerCrudServices();
    }

    private function registerCrudServices() {
        foreach (config('crud.services') as $service => $meta) {
            if (!is_subclass_of($service, CrudService::class))
                throw new InvalidArgumentException("Class \"$service\" is not instance of CrudService");

            $this->app->singleton($service);
        }
    }
}
