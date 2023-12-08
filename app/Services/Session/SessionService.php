<?php
declare(strict_types=1);

namespace App\Services\Session;

use Illuminate\Support\Facades\Session;

class SessionService
{
    private const CREDITS_KEY = 'credits';
    private const DEFAULT_CREDITS_COUNT = 10;

    /**
     * @return void
     */
    public function init(): void
    {
        Session::regenerate();
        $this->put(self::DEFAULT_CREDITS_COUNT);
    }

    /**
     * @return int
     */
    public function balance(): int
    {
        return Session::get(self::CREDITS_KEY, 0);
    }

    /**
     * @param int $credits
     *
     * @return void
     */
    public function put(int $credits = 0): void
    {
        Session::put([self::CREDITS_KEY => $credits]);
    }

    /**
     * @return void
     */
    public function forget(): void
    {
        Session::forget(self::CREDITS_KEY);
    }
}
