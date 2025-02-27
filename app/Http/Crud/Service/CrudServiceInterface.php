<?php

namespace App\Http\Crud\Service;

use Illuminate\Database\Eloquent\Model;

interface CrudServiceInterface {
    /**
     * Check is model uses given trait
     * @param string $trait
     * @return bool
     */
    public function isModelUse(string $trait): bool;

    /**
     * Creates empty model instance
     * @return Model
     */
    public function makeEmptyModel(): Model;
}
