<?php

namespace App;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class TikTacToe
{

    protected $player;
    protected $board;

    public function __construct(Player $player, Board $board)
    {
        $this->player = $player;
        $this->board = $board;
    }

    public function base(Request $request, Application $app)
    {
        if ($request->get('board')) {
            $boardStrng = $request->get('board');

            try {
                $currentBoard = $this->board->createNewBoardInstance($boardStrng);
                $response = $this->player->playGame($currentBoard);
                // $app['monolog']->addDebug($response);

                return $response;
            } catch (\Exception $e) {
                return new JsonResponse("", 400);
            }
        }

        return new JsonResponse("", 400);
    }
}
