<?php
use PHPUnit\Framework\TestCase;
require_once './othello.php';
class OthelloTest extends TestCase
{

    public function testOpponentColor()
    {
       $this->assertEquals(WHITE, opponent_color(BLACK));

       $this->assertEquals(BLACK, opponent_color(WHITE));
    }

    function initial_board()
    {
        return [
        [WALL,WALL ,WALL ,WALL ,WALL ,WALL ,WALL ,WALL ,WALL ,WALL,],
        [WALL,BLANK,BLANK,BLANK,BLANK,BLANK,BLANK,BLANK,BLANK,WALL,],
        [WALL,BLANK,BLANK,BLANK,BLANK,BLANK,BLANK,BLANK,BLANK,WALL,],
        [WALL,BLANK,BLANK,BLANK,BLANK,BLANK,BLANK,BLANK,BLANK,WALL,],
        [WALL,BLANK,BLANK,BLANK,WHITE,BLACK,BLANK,BLANK,BLANK,WALL,],
        [WALL,BLANK,BLANK,BLANK,BLACK,WHITE,BLANK,BLANK,BLANK,WALL,],
        [WALL,BLANK,BLANK,BLANK,BLANK,BLANK,BLANK,BLANK,BLANK,WALL,],
        [WALL,BLANK,BLANK,BLANK,BLANK,BLANK,BLANK,BLANK,BLANK,WALL,],
        [WALL,BLANK,BLANK,BLANK,BLANK,BLANK,BLANK,BLANK,BLANK,WALL,],
        [WALL,WALL ,WALL ,WALL ,WALL ,WALL ,WALL ,WALL ,WALL ,WALL,],
        ];
    }

    public function testMobilitySingleDirection()
    {
        $single_direction_board = initial_board();

        //true case
        $this->assertEquals(true, mobility_single_direction($single_direction_board, 3, 4, 1, 0, BLACK));
        
        //false case
        $this->assertEquals(false, mobility_single_direction($single_direction_board, 6, 4, 1, 0, BLACK));

    }
    
    public function testMobilityPoint()
    {
        $board = initial_board();

        //true case
        $this->assertEquals(true, mobility_point($board, 3, 4, BLACK));

        //false case
        $this->assertEquals(false, mobility_point($board, 3, 4, WHITE));

    }

    public function testReverseSingleDirection()
    {
        $board = initial_board();
        $ret = [
            [WALL,WALL ,WALL ,WALL ,WALL ,WALL ,WALL ,WALL ,WALL ,WALL,],
            [WALL,BLANK,BLANK,BLANK,BLANK,BLANK,BLANK,BLANK,BLANK,WALL,],
            [WALL,BLANK,BLANK,BLANK,BLANK,BLANK,BLANK,BLANK,BLANK,WALL,],
            [WALL,BLANK,BLANK,BLANK,BLANK,BLANK,BLANK,BLANK,BLANK,WALL,],
            [WALL,BLANK,BLANK,BLANK,BLACK,BLACK,BLANK,BLANK,BLANK,WALL,],
            [WALL,BLANK,BLANK,BLANK,BLACK,WHITE,BLANK,BLANK,BLANK,WALL,],
            [WALL,BLANK,BLANK,BLANK,BLANK,BLANK,BLANK,BLANK,BLANK,WALL,],
            [WALL,BLANK,BLANK,BLANK,BLANK,BLANK,BLANK,BLANK,BLANK,WALL,],
            [WALL,BLANK,BLANK,BLANK,BLANK,BLANK,BLANK,BLANK,BLANK,WALL,],
            [WALL,WALL ,WALL ,WALL ,WALL ,WALL ,WALL ,WALL ,WALL ,WALL,],
            ];
        // true case
        $this->assertEquals($ret, reverse_single_direction($board, 3, 4, 1, 0, BLACK));

        //false case
        $this->assertEquals($ret, reverse_single_direction($board, 3, 4, 1, 0, WHITE));


    }


}