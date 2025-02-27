<?php

namespace App\Http\Crud\Service\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

/**
 * Trait QueryConcern contains common query builder management methods
 * @package App\Http\Crud\Concerns
 */
trait QueryConcern {

    /**
     * Parses query builder object from given target
     * Override `makeQuery` to add common scopes etc
     * @param string|Model|null $from
     * @return Builder
     */
    public function makeRawQuery($from = null): Builder {
        if (!$from) $from = $this->getModelClass();
        if (is_string($from) && is_subclass_of($from, Model::class)) return $from::query();
        if ($from instanceof Model) return $from->newQuery();
        if ($from instanceof Builder) return $from->newQuery();
        throw new InvalidArgumentException("Invalid query source passed");
    }

    /**
     * Makes new query builder object from given target
     * @param string|Model|null $from
     * @return Builder
     */
    public function makeQuery($from = null): Builder {
        return $this->makeRawQuery($from);
    }
}
