<?php

namespace App\Http\Controllers\Api;

use App\Http\Crud\Service\CrudService;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Throwable;

class ApiCrudController extends ApiController {
    protected CrudService $crud;

    public function __construct(CrudService $crudService = null) {
        if (!$crudService && property_exists($this, 'crudServiceClass')) {
            $crudService = app($this->crudServiceClass);
        }

        if (!$crudService || !$crudService instanceof CrudService) {
            $controllerClass = class_basename(static::class);
            throw new RuntimeException("Invalid crud service passed in \"$controllerClass\"");
        }

        $this->crud = $crudService;
    }

    public function routes(Router $router) {
        parent::routes($router);

        $router->get('', [static::class, 'list']);
        $router->get('count', [static::class, 'count']);
        $router->post('', [static::class, 'create']);
        $router->get('{id}', [static::class, 'show'])->where('id', '[0-9]+');
        $router->addRoute(['PUT', 'PATCH'], '{id}', [static::class, 'update'])
            ->where('id', '[0-9]+');
        $router->delete('{id}', [static::class, 'delete'])->where('id', '[0-9]+');
        $router->get('google-sheet', [static::class, 'getGoogleSheet']);
        $router->post('google-sheet', [static::class, 'setGoogleSheet']);
    }

    public function list(Request $request) {
        $user = $request->user();

        $params = [
            'offset' => $request->get('offset'),
            'limit'  => $request->get('limit'),
        ];

        return $this->crud->shortcutList($user, $params);
    }

    public function count() {
        $query = $this->crud->makeQueryForListing();

        $count = $query->count();

        return compact('count');
    }

    public function show(Request $request, $id) {
        $query = $this->crud->makeQueryForShow();

        $withTrashed = $request->boolean('withTrashed');
        if ($withTrashed) {
            $query = $query->withTrashed();
        }

        return $query->findOrFail($id);
    }

    public function create(Request $request) {
        $user = $request->user();
        $data = $request->all();

        $validator = $this->crud->makeCreateValidator($data);
        $validData = $validator->validate();

        DB::beginTransaction();

        try {
            $model = $this->crud->makeEmptyModel();
            $this->crud->shortcutCreate($model, $validData, $user);
            DB::commit();

            return $model;

        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(Request $request, string $id) {
        $user = $request->user();
        $data = $request->all();

        $query = $this->crud->makeQueryForFindForUpdate();

        $model = $query->findOrFail($id);

        $validator = $this->crud->makeUpdateValidator($model, $data);
        $validData = $validator->validate();

        DB::beginTransaction();

        try {
            $this->crud->shortcutUpdate($model, $validData, $user);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return $model;
    }

    public function delete(Request $request, $id) {
        $user = $request->user();
        $isSoftDelete = $this->crud->isModelUse(SoftDeletes::class);
        $restore = $isSoftDelete && $request->boolean('restore');

        $query = $this->crud->makeQueryForShow();

        if ($isSoftDelete) {
            $query = $query->withTrashed();
        }

        $model = $query->findOrFail($id);

        DB::beginTransaction();

        try {
            $this->crud->shortcutDelete($model, $user, $restore);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return $model;
    }

    public function getGoogleSheet() {
        $url = DB::table('google_sheets')
            ->where('crud_service', get_class($this->crud))
            ->pluck('url')
            ->first();
        return compact('url');
    }

    public function setGoogleSheet(Request $request) {
        $validated = $request->validate([
            'url' => 'nullable|string|regex:/\\/spreadsheets\\/d\\/[^\\/]+\\//',
        ]);

        $query = DB::table('google_sheets');
        $where = ['crud_service' => get_class($this->crud)];

        if ($validated['url']) {
            $query->updateOrInsert($where, ['url' => $validated['url']]);
        } else {
            $query->where($where)->delete();
        }
    }
}
