<?php

use App\Http\Crud\Core\DemoCrudService;
use App\Models\Demo;

return [
    'services' => [
        DemoCrudService::class => ['model' => Demo::class],
    ],
];
