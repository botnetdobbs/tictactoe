<?php

namespace App;

class Board
{
    const INDEX_MATCHES = [
        [0, 1, 2], [3, 4, 5],
        [6, 7, 8], [0, 3, 6],
        [1, 4, 7], [2, 5, 8],
        [0, 4, 8], [2, 4, 6]
    ];

    public $boardStrng;

    public function __construct($boardStrng = "         ")
    {
        $this->boardStrng = $boardStrng;
    }

    public function __toString(): string
    {
        return $this->boardStrng;
    }

    public function createNewBoardInstance($boardStrng)
    {
        // Accept both upper & lower case
        $boardStrng = strtolower(str_replace("+", " ", $boardStrng));

        $this->boardStringIsValid($boardStrng);

        $instance = new self($boardStrng);

        return $instance;
    }

    public function emptySlots()
    {
        $slots = [];
        foreach (str_split($this->boardStrng) as $key => $value) {
            if ($value === " ") {
                $slots[] = $key;
            }
        }
        return $slots;
    }

    public function isServerPlayTurn()
    {
        $charCounts = $this->countCharacters();
        $difference = $charCounts["o"] - $charCounts["x"];

        if ($difference === 0 || $difference < 0) {
            return true;
        }
        return false;
    }

    public function makeMove($position, $letter)
    {
        $board = str_split($this->boardStrng);
        $board[$position] = $letter;
        $this->boardStrng = implode("", $board);
        return $this;
    }

    public function getOpponentPositions()
    {
        $opponentPositions = [];
        foreach (str_split($this->boardStrng) as $key => $value) {
            if ($value === "x") {
                $opponentPositions[] = $key;
            }
        }
        return $opponentPositions;
    }

    private function countCharacters()
    {
        $characterCounts = [
            "x" => 0,
            "o" => 0,
            " " => 0
        ];
        foreach (str_split($this->boardStrng) as $value) {
            $characterCounts[$value] += 1;
        }
        if ((($characterCounts['o'] - $characterCounts['x']) > 1) || ($characterCounts['x'] - $characterCounts['o']) > 1) {
            throw new \Exception("Wrong state");
        }

        return $characterCounts;
    }

    public function checkForWin()
    {
        foreach ($this::INDEX_MATCHES as $match) {
            if (($this->boardStrng[$match[0]] === $this->boardStrng[$match[1]])
                && ($this->boardStrng[$match[1]] === $this->boardStrng[$match[2]] && $this->boardStrng[$match[2]] !== " ")
            ) {
                return true;
            }
        }
        return false;
    }

    private function boardStringIsValid($boardStrng)
    {
        $payload = str_replace(["o", "x", " "], "", $boardStrng);
        if (strlen($payload) !== 0) {
            throw new \Exception("Invalid letters/characters");
        }
        if (strlen($boardStrng) !== 9) {
            throw new \Exception("Wrong board size");
        }
    }
}
