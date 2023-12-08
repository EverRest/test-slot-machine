<?php
declare(strict_types=1);

namespace App\Livewire\Game;

use App\Services\Game\GameService;
use App\Services\Roll\RollService;
use App\Services\Session\SessionService;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Component;
use Throwable;

class GameComponent extends Component
{
    /**
     * @var int
     */
    public int $credits = 0;

    /**
     * @var array
     */
    public array $blocks = [];

    /**
     * @var string
     */
    public string $randomDirection = '';

    /**
     * @var string
     */
    public string $resultMessage = '';

    /**
     * @var bool
     */
    public bool $buttonsVisible = true;

    /**
     * @var bool
     */
    public bool $cashOutButtonVisible = true;

    public function __construct()
    {
        $this->initBlocks();
    }

    /**
     * @param SessionService $sessionService
     *
     * @return Application|Factory|View|ApplicationContract
     */
    public function render(SessionService $sessionService): Factory|View|Application|ApplicationContract
    {
        $this->credits = $sessionService->balance();
        return view('livewire.game.game-component');
    }

    /**
     * @param GameService $gameService
     * @param RollService $rollService
     * @param SessionService $cacheService
     *
     * @return void
     */
    public function roll(GameService $gameService, RollService $rollService, SessionService $cacheService): void
    {
        if ($this->credits < 1) {
            $this->resultMessage = 'You don\'t have enough of credits for the game!';
            return;
        }
        $this->cashOutButtonVisible = true;
        $this->credits--;
        $this->updateButtonsState();
        $this->blocks = $rollService->roll($this->credits);
        $reward = $gameService->reward($this->blocks);
        if ($reward > 0) {
            $this->resultMessage = 'Please try again!';
        } else {
            $this->resultMessage = "You won $reward credits!";
        }
        $rollService->saveHistory($reward, $this->blocks);
        $this->credits += $reward;
        $cacheService->put($this->credits);
    }

    /**
     * @throws Throwable
     */
    public function cashOut(GameService $gameService): void
    {
        $this->initBlocks();
        $this->updateButtonsState();
        $gameService->cashOut($this->credits);
        $this->credits = 0;
        $this->resultMessage = 'Cashed out successfully!';
    }

    /**
     * @return void
     */
    public function handleMouseOver(): void
    {
        $roll = $this->generateRandomSize(1, 100);
        if ($roll <= 50) {
            $randH = $this->generateRandomSize();
            $randW = $this->generateRandomSize();
            $this->randomDirection = "translate({$randH}px,{$randW}px)";

        } elseif ($roll <= 90) {
            $this->cashOutButtonVisible = false;
        }
    }

    /**
     * @return void
     */
    private function updateButtonsState(): void
    {
        $this->buttonsVisible = ($this->credits > 0);
    }

    /**
     * @return void
     */
    private function initBlocks(): void
    {
        $this->blocks = ['X', 'X', 'X'];
    }

    /**
     * @param int $min
     * @param int $max
     *
     * @return int
     */
    private function generateRandomSize(int $min = -100, int $max = 100): int
    {
        return rand($min, $max);
    }
}

