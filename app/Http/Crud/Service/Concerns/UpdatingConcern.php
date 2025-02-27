<?php

namespace App\Http\Crud\Service\Concerns;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Model;

trait UpdatingConcern {

    /**
     * Fill local fields and relations with given data.
     * Should be overridden.
     * @param Model $model
     * @param array $data
     * @return Model
     */
    public function fillBeforeUpdate(Model $model, array $data): Model {
        $model->fill($data);
        $this->fillServicesBeforeUpdate($model, $data);
        $this->fillCommonsBefore($model, $data);
        return $model;
    }

    /**
     * Fills model local data with service data.
     * @param Model $model
     * @param array $data
     */
    public function fillServicesBeforeUpdate(Model $model, array $data) {
        if ($this->isModelUse(HasTimestamps::class)) {
            $model->setUpdatedAt(now());
        }
        // TODO add all service fills
    }

    /**
     * Fill remote fields and relations with given data.
     * Should be overridden.
     * @param Model $model
     * @param array $data
     * @return Model
     */
    public function fillAfterUpdate(Model $model, array $data): Model {
        $this->fillServicesAfterUpdate($model, $data);
        $this->fillCommonsAfter($model, $data);
        return $model;
    }

    /**
     * Fills model remote data with service data.
     * @param Model $model
     * @param array $data
     */
    public function fillServicesAfterUpdate(Model $model, array $data) {
    }
}
