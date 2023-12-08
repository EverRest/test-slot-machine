<?php
declare(strict_types=1);

namespace App\Repositories\Repository;

use App\Repositories\Repository\Contracts\RepositoryInterface;
use App\Repositories\Repository\Traits\HasDestroy;
use App\Repositories\Repository\Traits\HasSort;
use App\Repositories\Repository\Traits\HasUpdate;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class Repository implements RepositoryInterface
{
    use HasDestroy;
    use HasUpdate;
    use HasSort;

    /**
     * @var string
     */
    protected string $model;

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->query()->count();
    }

    /**
     * @param Collection $data
     *
     * @return Model
     */
    public function store(Collection $data): Model
    {
        return $this->model::create($data->toArray())->refresh();
    }

    /**
     * @param Collection $data
     *
     * @return mixed
     */
    public function storeMany(Collection $data): mixed
    {
        return $this->model::insert($data->toArray());
    }

    /**
     * @param Collection $data
     *
     * @return Model
     */
    public function firstOrCreate(Collection $data): Model
    {
        return $this->model::firstOrCreate($data->toArray());
    }

    /**
     * @param int $id
     *
     * @return Model
     */
    public function findOrFail(int $id): Model
    {
        return $this->model::findOrFail($id);
    }

    /**
     * @return void
     */
    public function destroyAll(): void
    {
        in_array(SoftDeletes::class, class_uses($this->query()->getModel()), true) ?
            $this->model::all()->each(fn(Model $model) => $model->delete()) :
            $this->model::truncate();
    }

    /**
     * Get a list of models
     *
     * @param Collection $data
     *
     * @return Paginator
     */
    public function index(Collection $data): Paginator
    {
        $query = $this->search($data);

        if (Arr::has($data, 'exclude_id')) {
            $query->when(
                Arr::get($data, 'exclude_id'),
                fn($query) => $query->whereNotIn('id', Arr::get($data, 'exclude_id', []))
            );
            Arr::forget($data, 'exclude_id');
        }

        $this->filter(
            $query,
            Arr::except(
                $data->toArray(),
                [
                    Config::get('pagination.search_key'),
                    Config::get('pagination.sort_key'),
                    Config::get('pagination.order_key'),
                    Config::get('pagination.limit_key'),
                    Config::get('pagination.page_key')
                ]
            )
        );
        $this->sort(
            $query,
            $data->get(Config::get('pagination.sort_key'), Config::get('pagination.order_key')),
        );
        return $this->paginate($query, Collection::make(Arr::only($data->toArray(), Config::get('pagination.limit_key'))));
    }

    /**
     * @return EloquentBuilder
     */
    public function query(): EloquentBuilder
    {
        /**
         * @var Model $model
         */
        $model = App::make($this->model);

        return $model::query();
    }

    /**
     * @param Collection $data
     *
     * @return EloquentBuilder
     */
    protected function search(Collection $data): EloquentBuilder
    {
        return $this->query();
    }

    /**
     * @param $query
     * @param array $filter
     *
     * @return EloquentBuilder
     */
    protected function filter($query, array $filter): EloquentBuilder
    {
        $query->when($filter, fn($query) => $this->applyFilter($query, $filter));

        return $query;
    }

    /**
     * @param mixed $query
     * @param array $filter
     *
     * @return mixed
     */
    protected function applyFilter(mixed $query, array $filter): mixed
    {
        foreach ($filter as $filterKey => $filterValue) {
            if (!is_string($filterKey)) {
                continue;
            }

            if (is_array($filterValue)) {
                $query->whereIn($filterKey, $filterValue);
            } else {
                $query->where($filterKey, $filterValue);
            }
        }

        return $query;
    }

    /**
     * @param $query
     * @param Collection $data
     *
     * @return Paginator
     */
    protected function paginate($query, Collection $data): Paginator
    {
        $limit = $data->get(Config::get("pagination.limit_key")) ?: Config::get('pagination.limit_per_page');
        return $query->paginate($limit);
    }

    /**
     * @return Model
     */
    protected function model(): Model
    {
        return new $this->model();
    }

    /**
     * @param string $sort
     *
     * @return string
     */
    private function prepareMultipleLanguages(string $sort): string
    {
        if (in_array($sort, Config::get('multiple-languages-fields'))) {
            $sort = sprintf('%s_%s', $sort, Config::get('app.locale'));
        }

        return $sort;
    }
}
