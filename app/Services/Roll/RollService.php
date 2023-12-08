<?php
declare(strict_types=1);

namespace App\Services\Roll;

use App\Enum\FruitEnum;
use App\Repositories\Roll\RollRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class RollService
{
    /**
     * @param RollRepository $repository
     */
    public function __construct(readonly protected RollRepository $repository)
    {
    }

    /**
     * @param int $credits
     *
     * @return array
     */
    public function roll(int $credits): array
    {
        $symbols = FruitEnum::toArray();

        return $credits < 40 || ($credits <= 60 && rand(1, 100) > 70) || ($credits > 60 && rand(1, 100) > 40) ?
            array_map(fn() => $symbols[array_rand($symbols)], ['', '', '']) : $this->roll($credits);
    }


    /**
     * @param int $reward
     * @param array $blocks
     *
     * @return Model
     */
    public function saveHistory(int $reward, array $blocks): Model
    {
        return $this->repository->store(Collection::make([
            'user_id' => intval(Auth::id()),
            'layout' => $blocks,
            'reward_credits' => $reward,
        ]));
    }
}
