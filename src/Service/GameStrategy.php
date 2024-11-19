<?php

namespace App\Service;

use App\Enum\Player;

class GameStrategy
{
    public function __construct(private Player $activePlayer = Player::O, private Player $opponentPlayer = Player::X)
    {
        //
    }

    public function playGame(Board $board): string
    {
        if (!$board->isServerPlayTurn()) {
            throw new \InvalidArgumentException("Not my turn");
        }
        if ($board->checkForWin()) {
            return (string) $board;
        }

        $availableSlots = $board->emptySlots();

        if (empty($availableSlots)) {
            return (string) $board;
        }

        $serverSlot = $this->checkforWin($board, $this->activePlayer);
        if ($serverSlot) {
            return (string) $board->makeMove($serverSlot, $this->activePlayer);
        }

        $opponentPlayerWinningSlot = $this->checkforWin($board, $this->opponentPlayer);
        if ($opponentPlayerWinningSlot) {
            return (string) $board->makeMove($opponentPlayerWinningSlot, $this->activePlayer);
        }

        return (string) $board->makeMove(
            $this->getNextPossibleBestMove($board),
            $this->activePlayer
        );
    }

    private function checkforWin(Board $board, Player $player): ?int
    {
        foreach ($board->emptySlots() as $slot) {
            $newBoard = $board->makeMove($slot, $player);
            if ($newBoard->checkForWin()) {
                return $slot;
            }
        }
        return null;
    }

    private function getNextPossibleBestMove(Board $board): int
    {
        $emptySlots = $board->emptySlots();
        $opponent = $board->getOpponentPositions();

        if (count($emptySlots) === 8) {
            if (in_array($opponent[0], [1, 3, 5, 7])) {
                return 0; // Take corner when opponent takes edge
            }
            if ($opponent[0] === 4) {
                return 0; // Take corner when opponent takes center
            }
            return 4; // Take center otherwise
        }

        if (count($emptySlots) === 7) {
            if ($opponent[0] === 4) {
                return 2; // Strategic diagonal play
            }
        }

        // Handle other cases
        foreach ([0, 2, 6, 8] as $corner) {
            if (in_array($corner, $emptySlots)) {
                return $corner;
            }
        }

        return $emptySlots[0];
    }
}
