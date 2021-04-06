<?php

use App\Board;

/**
 * Test individual class methods and their interactions together if any
 */
class BoardTest extends BaseTestCase
{
    public function setUp(): void
    {
        $this->board = new Board();
    }

    public function testBoardDefaultsToEmpty()
    {
        $this->assertEquals("         ", (string) $this->board);
    }

    public function testBoardGeneratesFromInputString()
    {
        $this->assertEquals(" x xxooo ", $this->board->createNewBoardInstance("+x+xxooo+"));
    }

    public function testBoardGenerationFailsOnInvalidCharacters()
    {
        $this->expectException(\Exception::class);
        $this->board->createNewBoardInstance("+x+xqogo+");
    }

    public function testBoardGenerationFailsOnIncorrectBoardSIze()
    {
        $this->expectException(\Exception::class);
        $this->board->createNewBoardInstance("xxxxxxooo+");
    }

    public function testBoardReturnsCorrectEmptySlots()
    {
        $this->assertEquals([0, 1, 2, 3, 4, 5, 6, 7, 8], $this->board->emptySlots());

        $newBoard = $this->board->createNewBoardInstance("+xx+xooo+");
        $this->assertEquals([0, 3, 8], $newBoard->emptySlots());
    }

    public function testBoardCanHandlePlayerMovement()
    {
        $newBoard = $this->board->makeMove(4, "x");
        $this->assertEquals("    x    ", (string) $newBoard);
    }

    public function testBoardCanCorrectlyDetermineTheServersPlayingTurn()
    {
        $newBoard = $this->board->createNewBoardInstance("+++xxoo++");
        $this->assertTrue($newBoard->isServerPlayTurn());

        $newBoard1 = $this->board->createNewBoardInstance("+x+xooo++");
        $this->assertFalse($newBoard1->isServerPlayTurn());
    }

    public function testBoardCanGetTheServerOpponentPositions()
    {
        $newBoard = $this->board->createNewBoardInstance("+++xxoo++");
        $this->assertEquals([3, 4], $newBoard->getOpponentPositions());
    }

    public function testBoardCanReturnCorrectIndividualCountsOfCharacters()
    {

        $newBoard = $this->board->createNewBoardInstance("+++xxoo++");
        $characterCounts = $this->callMethod($newBoard, 'countCharacters');

        $this->assertEquals(2, $characterCounts["x"]);
        $this->assertEquals(5, $characterCounts[" "]);
        $this->assertEquals(2, $characterCounts["o"]);
    }

    public function testBoardCanCheckForWin()
    {
        $newBoard = $this->board->createNewBoardInstance("+++xxoo++");
        $isWin = $newBoard->checkForWin();

        $this->assertFalse($isWin);

        $newBoard1 = $this->board->createNewBoardInstance("xxxooxo++");
        $isWin1 = $newBoard1->checkForWin();

        $this->assertTrue($isWin1);
    }
}
