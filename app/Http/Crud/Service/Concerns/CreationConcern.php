<?php

namespace App\Http\Crud\Service\Concerns;

use Illuminate\Database\Eloquent\Model;

trait CreationConcern {

    /**
     * Make empty model
     * @return Model
     */
    public function makeEmptyModel(): Model {
        $modelClass = $this->getModelClass();
        return new $modelClass();
    }

    /**
     * Fill local fields and relations with given data.
     * Should be overridden.
     * @param Model $model
     * @param array $data
     * @return Model
     */
    public function fillBeforeCreate(Model $model, array $data): Model {
        $model->fill($data);
        $this->fillServicesBeforeCreate($model, $data);
        $this->fillCommonsBefore($model, $data);
        return $model;
    }

    /**
     * Fills model local data with service data.
     * @param Model $model
     * @param array $data
     */
    public function fillServicesBeforeCreate(Model $model, array $data) {
        // TODO add all service fills
    }

    public function fillCommonsBefore(Model $model, array $data) {
    }

    /**
     * Fill remote fields and relations with given data.
     * Should be overridden.
     * @param Model $model
     * @param array $data
     * @return Model
     */
    public function fillAfterCreate(Model $model, array $data): Model {
        $this->fillServicesAfterCreate($model, $data);
        $this->fillCommonsAfter($model, $data);
        return $model;
    }

    /**
     * Fills model remote data with service data.
     * @param Model $model
     * @param array $data
     */
    public function fillServicesAfterCreate(Model $model, array $data) {
    }

    public function fillCommonsAfter(Model $model, array $data) {
    }
}
