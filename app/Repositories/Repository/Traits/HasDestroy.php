<?php
declare(strict_types=1);

namespace App\Repositories\Repository\Traits;

use Illuminate\Database\Eloquent\Model;
use Throwable;

trait HasDestroy
{
    /**
     * Delete the model from the database within a transaction.
     *
     * @param Model $model
     * @param bool $force
     *
     * @return Model
     * @throws Throwable
     */
    public function destroy(Model $model, bool $force = false): Model
    {
        if ($force) {
            $model->forceDelete();
        } else {
            $model->deleteOrFail();
        }
        return $model;
    }
}
