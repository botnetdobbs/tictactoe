<?php

namespace App\Service;

use App\Enum\Player;
use Exception;

class Board
{
    const INDEX_MATCHES = [
        [0, 1, 2], [3, 4, 5],
        [6, 7, 8], [0, 3, 6],
        [1, 4, 7], [2, 5, 8],
        [0, 4, 8], [2, 4, 6]
    ];

    public function __construct(public string $boardStrng = "         ")
    {}

    public function __toString(): string
    {
        return $this->boardStrng;
    }

    public function createNewBoardInstance(string $boardStrng): self
    {
        // Accept both upper & lower case
        $boardStrng = strtolower(str_replace("+", " ", $boardStrng));

        $this->validateBoardString($boardStrng);

        return new self($boardStrng);
    }

    /**
     * @return array<int>
     */
    public function emptySlots(): array
    {
        return array_keys(
            array_filter(
                str_split($this->boardStrng),
                fn(string $value): bool => $value === Player::EMPTY->value
            )
        );
    }

    public function isServerPlayTurn(): bool
    {
        $charCounts = $this->countCharacters();
        return $charCounts[Player::O->value] <= $charCounts[Player::X->value];
    }

    public function makeMove(int $position, Player $player): self
    {
        $board = str_split($this->boardStrng);
        $board[$position] = $player->value;
        return new self(implode("", $board));
    }

    /**
     * @return array<int>
     */
    public function getOpponentPositions(): array
    {
        return array_keys(
            array_filter(
                str_split($this->boardStrng),
                fn(string $value): bool => $value === Player::X->value
            )
        );
    }

    /**
     * @throws Exception
     * @return array<string, int>
     */
    private function countCharacters(): array
    {
        $counts = array_count_values(str_split($this->boardStrng));
        $xCount = $counts[Player::X->value] ?? 0;
        $oCount = $counts[Player::O->value] ?? 0;

        if(abs($xCount - $oCount) > 1) {
            throw new \InvalidArgumentException("Wrong state");
        }

        return $counts;
    }

    public function checkForWin(): bool
    {
        foreach (self::INDEX_MATCHES as $match) {
            $values = array_map(
                fn(int $index) => $this->boardStrng[$index],
                $match
            );

            if(count(array_unique($values)) === 1 && $values[0] !== Player::EMPTY->value) {
                return true;
            }
        }
        return false;
    }

    /**
     * @throws \InvalidArgumentException 
     */
    private function validateBoardString(string $boardStrng): void
    {
        if (strlen($boardStrng) !== 9) {
            throw new \InvalidArgumentException("Wrong board size");
        }

        $validChars = array_map(
            fn(Player $player): string => $player->value,
            Player::cases()
        );

        $payload = str_replace($validChars, "", $boardStrng);

        if (strlen($payload) !== 0) {
            throw new \InvalidArgumentException("Invalid letters/characters");
        }
    }
}
