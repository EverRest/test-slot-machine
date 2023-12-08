<?php
declare(strict_types=1);

namespace App\Repositories\Repository\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Throwable;

interface RepositoryInterface
{

    /**
     * @return int
     */
    public function count(): int;

    /**
     * @param Collection $data
     *
     * @return Model
     */
    public function store(Collection $data): Model;

    /**
     * @param Collection $data
     *
     * @return Model
     */
    public function firstOrCreate(Collection $data): Model;

    /**
     * @param Collection $data
     *
     * @return mixed
     */
    public function storeMany(Collection $data): mixed;

    /**
     * @param int $id
     *
     * @return Model
     */
    public function findOrFail(int $id): Model;

    /**
     * @return void
     */
    public function destroyAll(): void;

    /**
     * Update and refresh model
     *
     * @param Model $model
     * @param Collection $data
     *
     * @return Model
     */
    public function update(Model $model, Collection $data): Model;

    /**
     * @param Model $model
     * @param Collection $data
     *
     * @return Model
     * @throws Throwable
     */
    public function updateOrFail(Model $model, Collection $data): Model;

    /**
     * Delete the model from the database within a transaction.
     *
     * @param Model $model
     * @param bool $force
     *
     * @return Model
     * @throws Throwable
     */
    public function destroy(Model $model, bool $force = false): Model;
}
