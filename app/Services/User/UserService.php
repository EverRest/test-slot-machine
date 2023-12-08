<?php
declare(strict_types=1);

namespace App\Services\User;

use App\Repositories\User\UserRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Throwable;

class UserService
{
    /**
     * @param UserRepository $repository
     */
    public function __construct(readonly protected UserRepository $repository)
    {
    }

    /**
     * @param mixed $user
     * @param int $credits
     *
     * @return Model
     * @throws Throwable
     */
    public function updateBalance(mixed $user, int $credits): Model
    {
        return $this->repository
            ->updateOrFail(
                $user,
                Collection::make(['balance' => $credits + $user->balance,])
            );
    }
}
