<?php

namespace App;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class Player
{
    private $activePlayer;
    private $opponentPlayer;

    public function __construct()
    {
        $this->activePlayer = "o";
        $this->opponentPlayer = "x";
    }

    public function playGame(Board $board): string
    {
        if (!$board->isServerPlayTurn()) {
            throw new \Exception("Not my turn");
        }
        if ($board->checkForWin()) {
            return (string) $board;
        }
        $availableSlots = $board->emptySlots();

        if (!empty($availableSlots)) {
            $serverSlot = $this->checkforWin($board, $this->activePlayer);
            $opponentPlayerWinningSlot = $this->checkforWin($board, $this->opponentPlayer);

            //Block three-in-a-row unless server's move wins
            if (!$serverSlot && $opponentPlayerWinningSlot) {
                $serverSlot = $opponentPlayerWinningSlot;
            } else {
                $serverSlot = $this->getNextPossibleBestMove($board);
            }

            $board->makeMove($serverSlot, $this->activePlayer);
        }

        return (string) $board;
    }

    /**
     * Brute Force. Get win if any
     */
    private function checkforWin(Board $board, string $activePlayer)
    {
        foreach ($board->emptySlots() as $winSlot) {
            $newBoardWin = new Board($board->boardStrng);
            $newBoardWin->makeMove($winSlot, $activePlayer);
            if ($newBoardWin->checkForWin()) {
                return $winSlot;
            }
        }
        return null;
    }

    /**
     * Minimax algorithm can be used to optimize and as a result combine the 2 methods
     */
    private function getNextPossibleBestMove(Board $board)
    {
        $opponentPlayerPositions = $board->getOpponentPositions();
        $emptyPositions = $board->emptySlots();
        $serverPositions = array_diff(
            [0, 1, 2, 3, 4, 5, 6, 7, 8],
            $opponentPlayerPositions,
            $emptyPositions
        );

        if (count($emptyPositions) === 8) {
            if (in_array($opponentPlayerPositions[0], [1, 3, 5, 7])) {
                return 4;
            }
            if ($opponentPlayerPositions[0] === 4) {
                $corners = [0, 2, 6, 8];
                return $corners[array_rand($corners)];
            }
            if (in_array($opponentPlayerPositions[0], [0, 2, 6, 8])) {
                $edges = [1, 3, 5, 7];
                return $edges[array_rand($edges)];
            }
        }

        if (count($emptyPositions) === 7) {
            if ($opponentPlayerPositions[0] === 4 && in_array($serverPositions[0], [0, 2, 6, 8])) {
                $corners = array_diff([1, 3, 5, 7], $serverPositions);
                return $corners[array_rand($corners)];
            }
            if ($opponentPlayerPositions[0] === 4 && in_array($serverPositions[0], [0, 2, 6, 8])) {
                $corners = array_diff([1, 3, 5, 7], $serverPositions);
                return $corners[array_rand($corners)];
            }
        }
        return $emptyPositions[0];
    }
}
