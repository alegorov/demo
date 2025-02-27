<?php

namespace App\Http\Crud\Service\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait RetrievingConcern {

    /**
     * Make base query for list action
     * @param null $from
     * @return Builder
     */
    public function makeQueryForListing($from = null): Builder {
        return $this->makeQuery($from);
    }

    /**
     * Make base query for list action
     * @param null $from
     * @return Builder
     */
    public function makeQueryForShow($from = null): Builder {
        return $this->makeQuery($from);
    }

    /**
     * Make retrieving base query for update action
     * @param null $from
     * @return Builder
     */
    public function makeQueryForFindForUpdate($from = null): Builder {
        return $this->makeQuery($from);
    }
}
