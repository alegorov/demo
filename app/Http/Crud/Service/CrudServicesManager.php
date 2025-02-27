<?php

namespace App\Http\Crud\Service;

use Illuminate\Database\Eloquent\Model;
use RuntimeException;

class CrudServicesManager {

    /**
     * @param $service
     * @return mixed metadata of the crud service
     */
    public function getServiceMetadata($service) {
        if (!is_string($service)) {
            $service = get_class($service);
        }

        if (!is_subclass_of($service, CrudService::class)) {
            throw new RuntimeException("Class \"$service\" isn't crud service");
        }

        $servicesDefinition = config('crud.services');

        if (!array_key_exists($service, $servicesDefinition)) {
            throw new RuntimeException("Crud service \"$service\" not found in `crud.services` config");
        }

        return $servicesDefinition[$service];
    }

    /**
     * @param $model
     * @return class-string crud service class by its model
     */
    public function getServiceByModel($model): string {
        if (!is_string($model)) {
            $model = get_class($model);
        }

        if (!is_subclass_of($model, Model::class)) {
            throw new RuntimeException("Class \"$model\" isn't a model");
        }

        $servicesDefinition = config('crud.services');

        foreach ($servicesDefinition as $service => $meta) {
            if (!is_array($meta)) {
                continue;
            }

            if (($meta['model'] ?? null) === $model) {
                return $service;
            }
        }

        throw new RuntimeException("Cannot find crud service for model \"$model\"");
    }

    /**
     * @return class-string[] List of all crud services classes
     */
    public function getCrudServicesClasses(): array {
        return array_keys(config('crud.services'));
    }
}
