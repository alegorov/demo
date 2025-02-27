<?php

namespace App\Http\Crud\Service;

use App\Http\Crud\Service\Concerns\CreationConcern;
use App\Http\Crud\Service\Concerns\DeletingConcern;
use App\Http\Crud\Service\Concerns\QueryConcern;
use App\Http\Crud\Service\Concerns\RetrievingConcern;
use App\Http\Crud\Service\Concerns\ShortcutConcern;
use App\Http\Crud\Service\Concerns\UpdatingConcern;
use App\Http\Crud\Service\Concerns\ValidationConcern;
use Illuminate\Database\Eloquent\Model;
use RuntimeException;

abstract class CrudService implements CrudServiceInterface {
    use ValidationConcern, QueryConcern, CreationConcern,
        UpdatingConcern, DeletingConcern, RetrievingConcern,
        ShortcutConcern;

    protected string $modelClass;
    protected array $classUses;

    public function __construct(CrudServicesManager $manager) {
        if (!isset($this->modelClass)) {
            $service = static::class;
            $meta = $manager->getServiceMetadata($service);
            $model = $meta['model'] ?? '';
            if (!strlen($model))
                throw new RuntimeException("Model for service \"$service\" not found in `crud.services` config");
            $this->modelClass = $model;
        }

        if (!is_subclass_of($this->modelClass, Model::class))
            throw new RuntimeException("Class \"$this->modelClass\" isn't a model");
    }

    public function getModelClass(): string {
        return $this->modelClass;
    }

    public function isModelUse(string $trait): bool {
        $this->classUses ??= class_uses_recursive($this->getModelClass());

        return array_key_exists($trait, $this->classUses);
    }
}
