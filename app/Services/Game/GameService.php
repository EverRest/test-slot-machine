<?php
declare(strict_types=1);

namespace App\Services\Game;

use App\Services\Session\SessionService;
use App\Services\User\UserService;
use Illuminate\Support\Facades\Auth;
use Throwable;

class GameService
{
    public const DEFAULT_CREDITS = 10;
    public const CREDITS_KEY = 'credits';

    /**
     * @var array|int[]
     */
    protected array $rewards = [
        'cherry' => 10,
        'lemon' => 20,
        'orange' => 30,
        'watermelon' => 40,
    ];

    /**
     * @param UserService $userService
     * @param SessionService $sessionService
     */
    public function __construct(
        private readonly UserService    $userService,
        private readonly SessionService $sessionService,
    )
    {
    }

    /**
     * @param int $credits
     *
     * @return void
     * @throws Throwable
     */
    public function cashOut(int $credits): void
    {
        $this->userService->updateBalance(Auth::user(), $credits);
        $this->sessionService->forget();
    }

    /**
     * @param array $blocks
     *
     * @return int
     */
    public function reward(array $blocks): int
    {
        $reward = 0;
        $counts = array_count_values($blocks);
        if (count($counts) === 1) {
            $b = array_pop($blocks);
            $reward = $this->rewards[$b];
        }

        return $reward;
    }
}
