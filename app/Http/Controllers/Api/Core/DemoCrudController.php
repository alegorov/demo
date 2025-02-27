<?php

namespace App\Http\Controllers\Api\Core;

use App\Http\Controllers\Api\ApiCrudController;
use App\Http\Crud\Core\DemoCrudService;

class DemoCrudController extends ApiCrudController {
    public function __construct(DemoCrudService $crudService) {
        parent::__construct($crudService);
    }
}
