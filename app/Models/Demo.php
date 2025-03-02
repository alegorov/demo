<?php

namespace App\Models;

use App\Http\Crud\Service\ModelStatus;
use App\Models\Traits\BaseModelTraits;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Demo extends Model {
    use BaseModelTraits;

    protected $fillable = [
        'name',
        'email',
        'status',
    ];

    public function scopeAllowed(Builder $query): void {
        $query->where('status', ModelStatus::Allowed->value);
    }
}
