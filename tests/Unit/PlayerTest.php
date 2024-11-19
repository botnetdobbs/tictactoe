<?php

namespace App\Tests\Unit;

use App\Service\Board;
use App\Service\GameStrategy;
use App\Tests\BaseTestCase;

class PlayerTest extends BaseTestCase
{
    private GameStrategy $gameStrategy;
    private Board $board;

    public function setUp(): void
    {
        $this->gameStrategy = new GameStrategy();
        $this->board = new Board();
    }

    public function testServerReturnsTheBestPossibleMove(): void
    {
        $board = $this->board->createNewBoardInstance("+xxo++o++");

        $response = $this->gameStrategy->playGame($board);

        $this->assertEquals("oxxo  o  ", $response);
    }

    public function testServerBlocksNextOpponentsWinPosition(): void
    {
        $board = $this->board->createNewBoardInstance("o+x+xo+ox");

        $response = $this->gameStrategy->playGame($board);

        $this->assertEquals("o x xooox", $response);
    }

    public function testServerTakesNextPossibleWinPossition(): void
    {
        $board = $this->board->createNewBoardInstance("++xoxxo++");

        $response = $this->gameStrategy->playGame($board);

        $this->assertEquals("  xoxxo o", $response);
    }
}