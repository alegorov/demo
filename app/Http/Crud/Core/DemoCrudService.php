<?php

namespace App\Http\Crud\Core;

use App\Http\Crud\Service\CrudService;
use App\Http\Crud\Service\ModelStatus;
use Illuminate\Validation\Rule;

class DemoCrudService extends CrudService {
    public function getModelCreateValidationRules(): array {
        return [
            'name'   => 'required|string',
            'email'  => 'required|email',
            'status' => [
                'required',
                'integer',
                Rule::in([
                    ModelStatus::Allowed->value,
                    ModelStatus::Prohibited->value,
                ]),
            ],
        ];
    }
}
