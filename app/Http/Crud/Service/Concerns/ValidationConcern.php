<?php

namespace App\Http\Crud\Service\Concerns;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;

trait ValidationConcern {

    /**
     * Returns laravel validation rules for model.
     * Put here only current model specific rules.
     * You can override common rules here.
     * @param Model $model
     * @return array
     */
    public function getModelUpdateValidationRules(Model $model): array {
        $rules = $this->getModelCreateValidationRules();

        foreach ($rules as $key => &$value) {
            if (!Str::contains($key, '.')) {
                if (is_array($value)) {
                    if ($value) {
                        if ($value[0] != 'sometimes')
                            $value = array_merge(['sometimes'], $value);
                    } else {
                        $value = ['sometimes'];
                    }
                } else {
                    if (strlen($value)) {
                        if ($value != 'sometimes' && !Str::startsWith($value, 'sometimes|'))
                            $value = 'sometimes|' . $value;
                    } else {
                        $value = 'sometimes';
                    }
                }
            }
        }

        return $rules;
    }

    public function getServiceUpdateValidationRules(Model $model): array {
        return $this->getServiceCreateValidationRules();
    }

    /**
     * Makes validator by `$data` and generated validation
     * @param Model $model
     * @param array $data data to validate
     * @return Validator
     * @throws BindingResolutionException
     */
    public function makeUpdateValidator(Model $model, array $data): Validator {
        $rules = array_merge(
            $this->getServiceUpdateValidationRules($model),
            $this->getModelUpdateValidationRules($model)
        );

        return validator()->make($data, $rules);
    }

    /**
     * Returns laravel validation rules for model.
     * Put here only current model specific rules.
     * You can override common rules here.
     * @return array
     */
    public abstract function getModelCreateValidationRules(): array;

    public function getServiceCreateValidationRules(): array {
        return [];
    }

    /**
     * Makes validator by `$data` and generated validation
     * @param array $data data to validate
     * @return Validator
     */
    public function makeCreateValidator(array $data): Validator {
        $rules = array_merge(
            $this->getServiceCreateValidationRules(),
            $this->getModelCreateValidationRules()
        );

        return ValidatorFacade::make($data, $rules);
    }
}
