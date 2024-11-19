<?php

namespace App\Enum;

enum Player: string
{
    case X = 'x';
    case O = 'o';
    case EMPTY = ' ';

    public function isOpponent(): bool
    {
        return $this->value === self::X;
    }
}