<?php

namespace App\Http\Crud\Service\Concerns;

use Illuminate\Database\Eloquent\Model;

trait ShortcutConcern {
    public function shortcutList($user, array $params) {
        return $this->makeQueryForListing()
            ->orderBy('id')
            ->get();
    }

    public function shortcutCreate(Model $model, array $validData, $user) {
        $model = $this->fillBeforeCreate($model, $validData);

        $model->save();

        $this->fillAfterCreate($model, $validData);
    }

    public function shortcutUpdate(Model $model, array $validData, $user) {
        $model = $this->fillBeforeUpdate($model, $validData);

        $model->save();

        $this->fillAfterUpdate($model, $validData);
    }

    public function shortcutDelete(Model $model, $user, bool $restore) {
        if ($restore) {
            $this->restore($model);
        } else {
            $this->delete($model);
        }
    }
}
