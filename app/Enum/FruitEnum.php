<?php
declare(strict_types=1);

namespace App\Enum;

enum FruitEnum: string
{
    use EnumToArray;
    case cherry = 'C';
    case lemon = 'L';
    case orange = 'O';
    case watermelon = 'W';
}
