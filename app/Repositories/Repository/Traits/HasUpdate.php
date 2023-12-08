<?php
declare(strict_types=1);

namespace App\Repositories\Repository\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Throwable;

trait HasUpdate
{
    /**
     * Update and refresh model
     *
     * @param Model $model
     * @param Collection $data
     *
     * @return Model
     */
    public function update(Model $model, Collection $data): Model
    {
        $model->fill($data->toArray())->save();
        return $model->refresh();
    }

    /**
     * @param Model $model
     * @param Collection $data
     *
     * @return Model
     * @throws Throwable
     */
    public function updateOrFail(Model $model, Collection $data): Model
    {
        $model->updateOrFail($data->toArray());
        return $model;
    }
}
