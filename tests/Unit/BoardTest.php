<?php

namespace App\Tests\Unit;

use App\Enum\Player;
use App\Service\Board;
use App\Tests\BaseTestCase;

class BoardTest extends BaseTestCase
{
    private Board $board;

    public function setUp(): void
    {
        $this->board = new Board();
    }

    public function testBoardDefaultsToEmpty(): void
    {
        $this->assertEquals("         ", (string) $this->board);
    }

    public function testBoardGeneratesFromInputString(): void
    {
        $this->assertEquals(" x xxooo ", $this->board->createNewBoardInstance("+x+xxooo+"));
    }

    public function testBoardGenerationFailsOnInvalidCharacters(): void
    {
        $this->expectException(\Exception::class);
        $this->board->createNewBoardInstance("+x+xqogo+");
    }

    public function testBoardGenerationFailsOnIncorrectBoardSIze(): void
    {
        $this->expectException(\Exception::class);
        $this->board->createNewBoardInstance("xxxxxxooo+");
    }

    public function testBoardReturnsCorrectEmptySlots(): void
    {
        $this->assertEquals([0, 1, 2, 3, 4, 5, 6, 7, 8], $this->board->emptySlots());

        $newBoard = $this->board->createNewBoardInstance("+xx+xooo+");
        $this->assertEquals([0, 3, 8], $newBoard->emptySlots());
    }

    public function testBoardCanHandlePlayerMovement(): void
    {
        $newBoard = $this->board->makeMove(4, Player::X);
        $this->assertEquals("    x    ", (string) $newBoard);
    }

    public function testBoardCanCorrectlyDetermineTheServersPlayingTurn(): void
    {
        $newBoard = $this->board->createNewBoardInstance("+++xxoo++");
        $this->assertTrue($newBoard->isServerPlayTurn());

        $newBoard1 = $this->board->createNewBoardInstance("+x+xooo++");
        $this->assertFalse($newBoard1->isServerPlayTurn());
    }

    public function testBoardCanGetTheServerOpponentPositions(): void
    {
        $newBoard = $this->board->createNewBoardInstance("+++xxoo++");
        $this->assertEquals([3, 4], $newBoard->getOpponentPositions());
    }

    public function testBoardCanReturnCorrectIndividualCountsOfCharacters(): void
    {
        $newBoard = $this->board->createNewBoardInstance("+++xxoo++");
        $characterCounts = $this->callMethod($newBoard, 'countCharacters');

        $this->assertEquals(2, $characterCounts["x"]);
        $this->assertEquals(5, $characterCounts[" "]);
        $this->assertEquals(2, $characterCounts["o"]);
    }

    public function testBoardCanCheckForWin(): void
    {
        $newBoard = $this->board->createNewBoardInstance("+++xxoo++");
        $isWin = $newBoard->checkForWin();

        $this->assertFalse($isWin);

        $newBoard1 = $this->board->createNewBoardInstance("xxxooxo++");
        $isWin1 = $newBoard1->checkForWin();

        $this->assertTrue($isWin1);
    }
}
