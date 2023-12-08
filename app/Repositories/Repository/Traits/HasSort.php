<?php
declare(strict_types=1);

namespace App\Repositories\Repository\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;

trait HasSort
{
    /**
     * @param $query
     * @param Collection $data
     *
     * @return Builder
     */
    protected function sort($query, Collection $data): Builder
    {
        $sort = $this->getSortColumn($data);
        $order = $this->getDirectionColumn($data);
        $query->when($sort, function ($query) use ($sort, $order) {
            return $query->orderBy($sort, $order);
        });

        return $query;
    }

    /**
     * @param Collection $data
     *
     * @return string
     */
    protected function getSortColumn(Collection $data): string
    {
        return Arr::get($data, Config::get('pagination.sort_key'), Config::get('pagination.default_field'));
    }

    /**
     * @param Collection $data
     *
     * @return string
     */
    protected function getDirectionColumn(Collection $data): string
    {
        return $data->get(Config::get('pagination.order_key'), Config::get('pagination.order_direction'));
    }
}
