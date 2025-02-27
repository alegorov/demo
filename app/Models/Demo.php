<?php

namespace App\Models;

use App\Models\Traits\BaseModelTraits;
use Illuminate\Database\Eloquent\Model;

class Demo extends Model {
    use BaseModelTraits;

    protected $fillable = [
        'name',
        'email',
        'status',
    ];
}
