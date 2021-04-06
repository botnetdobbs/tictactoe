<?php

use App\Board;
use App\Player;

/**
 * Test individual class methods and their interactions together if any
 */
class PlayerTest extends BaseTestCase
{
    public function setUp(): void
    {
        $this->player = new Player();
        $this->board = new Board();
    }

    public function testServerReturnsTheBestPossibleMove()
    {
        $board = $this->board->createNewBoardInstance("+xxo++o++");

        $response = $this->player->playGame($board);

        $this->assertEquals("oxxo  o  ", $response);
    }

    public function testServerBlocksNextOpponentsWinPosition()
    {
        $board = $this->board->createNewBoardInstance("o+x+xo+ox");

        $response = $this->player->playGame($board);

        $this->assertEquals("o x xooox", $response);
    }

    public function testServerTakesNextPossibleWinPossition()
    {
        $board = $this->board->createNewBoardInstance("++xoxxo++");

        $response = $this->player->playGame($board);

        $this->assertEquals("  xoxxo o", $response);
    }

}