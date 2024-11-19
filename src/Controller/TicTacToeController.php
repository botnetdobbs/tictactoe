<?php

namespace App\Controller;

use App\Service\Board;
use App\Service\GameStrategy;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TicTacToeController extends AbstractController
{
    public function __construct(
        private Player $player,
        private Board $board
    ) {}

    #[Route('/', name: 'play', methods: ['GET'])]
    public function move(Request $request): JsonResponse
    {

        try {
            $boardString = $request->get('board');
            if (!$boardString) {
                throw new \InvalidArgumentException('Board string is required');
            }

            $currentBoard = $this->board->createNewBoardInstance($boardString);
            $response = $this->gameStrategy->playGame($currentBoard);
            return new JsonResponse($response);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse($e->getMessage(), JsonResponse::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Server error'], JsonResponse::HTTP_BAD_REQUEST);
        }
    }
}
