<?php

namespace App\Http\Crud\Service\Concerns;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

trait DeletingConcern {

    /**
     * Delete model
     * @param Model $model
     * @param false $force
     * @throws Exception
     */
    public function delete(Model $model, $force = false) {
        if ($force)
            $model->forceDelete();
        else
            $model->delete();
    }

    /**
     * Restore soft deleted model
     * @param Model&SoftDeletes $model
     */
    public function restore(Model $model) {
        $model->restore();
    }
}
