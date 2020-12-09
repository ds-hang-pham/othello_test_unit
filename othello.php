<?php

define('BLANK', 0);
define('BLACK', 1);
define('WHITE', -1);
define('WALL', 2);

/* 初期盤面を返す */
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

/* 盤面を表示 */
function print_board($board)
{
    $print_chars = [
    WALL => '|',
    BLANK => '+',
    BLACK => '@',
    WHITE => 'O',
    ];
    for ($i = 1; $i <= 8; ++$i) {
        for ($j = 1; $j <= 8; ++$j) {
            echo $print_chars[$board[$i][$j]];
        }
        echo "\n";
    }
    echo "\n";
}

/* 色を引数として、相手の色を返す */
function opponent_color($color)
{
    $arr = [
    BLACK => WHITE,
    WHITE => BLACK,
    ];
    return $arr[$color];
}

/* $boardに$colorを置ける場所を全て返す */
function mobility($board, $color)
{
    $ret = [];
    for ($i = 1; $i <= 8; ++$i) {
        for ($j = 1; $j <= 8; ++$j) {
            if (mobility_point($board, $i, $j, $color)) {
                $ret[] = [$i, $j];
            }
        }
    }
    return $ret;
}

/* $boardの$y, $xに$colorを置けるか判定 */
function mobility_point($board, $y, $x, $color)
{
    if ($board[$y][$x] != BLANK) {
        return false;
    }
    $direction = [
    [1,0], // 下
    [1,1], // 右下
    [0,1], // 右
    [-1,1], // 右上
    [-1,0], // 上
    [-1,-1], // 左上
    [0,-1], // 左
    [1,-1], // 左下
    ];
    foreach ($direction as $d) {
        if (mobility_single_direction($board, $y, $x, $d[0], $d[1], $color)) {
            return true;
        }
    }
    return false;
}

/* $boardの$y, $xに$colorを置けるか判定、ただし1方向しかチェックしない */
function mobility_single_direction($board, $y, $x, $inc_y, $inc_x, $color)
{
    $oc = opponent_color($color);
    $ct = 0;
    $y += $inc_y;
    $x += $inc_x;
    while ($board[$y][$x] == $oc) {
        $y += $inc_y;
        $x += $inc_x;
        ++$ct;
    }
    if ($board[$y][$x] == $color && $ct > 0) {
        return true;
    }
    return false;
}

/* 石を置いて結果を返す */
function put($board, $y, $x, $color)
{
    $ret = $board;
    $direction = [
    [1,0], // 下
    [1,1], // 右下
    [0,1], // 右
    [-1,1], // 右上
    [-1,0], // 上
    [-1,-1], // 左上
    [0,-1], // 左
    [1,-1], // 左下
    ];
    foreach ($direction as $d) {
        if (mobility_single_direction($board, $y, $x, $d[0], $d[1], $color)) {
            $ret = reverse_single_direction($ret, $y, $x, $d[0], $d[1], $color);
        }
    }
  /* 着手箇所に石を置く */
    $ret[$y][$x] = $color;
    return $ret;
}

/* 石を裏返す処理、ただし1方向のみ作用する */
function reverse_single_direction($board, $y, $x, $inc_y, $inc_x, $color)
{
    $ret = $board;
    $oc = opponent_color($color);
    $y += $inc_y;
    $x += $inc_x;
    while ($ret[$y][$x] == $oc) {
        $ret[$y][$x] = $color;
        $y += $inc_y;
        $x += $inc_x;
    }
    return $ret;
}

/* 両プレイヤーが着手不能でゲームオーバー */
function game_over($board)
{
    $mobility_black = mobility($board, BLACK);
    $mobility_white = mobility($board, WHITE);
    return empty($mobility_black) && empty($mobility_white);
}

/* 着手可能箇所からランダムに選択 */
function ai($board, $color)
{
    $mobility = mobility($board, $color);
    return $mobility[rand(0, count($mobility) - 1)];
}

/* パスかどうかチェック */
function pass($board, $color)
{
    $mobility = mobility($board, $color);
    return empty($mobility);
}

/* 結果集計 */
function result($board)
{
    $b = 0;
    $w = 0;
    for ($i = 1; $i <= 8; ++$i) {
        for ($j = 1; $j <= 8; ++$j) {
            if ($board[$i][$j] == BLACK) {
                ++$b;
            }
            if ($board[$i][$j] == WHITE) {
                ++$w;
            }
        }
    }
    echo 'BLACK:' . $b . "\n";
    echo 'WHITE:' . $w . "\n";
}

/* ゲーム本体 */
$board = initial_board();
$turn = BLACK;
while (!game_over($board)) {
    print_board($board);
    if (pass($board, $turn)) {
        $turn = opponent_color($turn);
        continue;
    }
    $select = ai($board, $turn);
    $board = put($board, $select[0], $select[1], $turn);
    $turn = opponent_color($turn);
}

print_board($board);
result($board);
