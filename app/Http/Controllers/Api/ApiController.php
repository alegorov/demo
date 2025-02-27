<?php

namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Routing\Router;

class ApiController extends BaseController {
    public function routes(Router $router) {
        $traits = class_uses_recursive(static::class);

        foreach ($traits as $trait) {
            $name = 'routes' . class_basename($trait);

            if (method_exists($this, $name)) {
                $this->{$name}($router);
            }
        }
    }
}
