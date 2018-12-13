<?php
/**
 * Date: 12/11/2018
 * Time: 12:11 PM
 */

/*--- Day 9: Marble Mania ---
 * You talk to the Elves while you wait for your navigation system to initialize.
 * To pass the time, they introduce you to their favorite marble game.
 *
 * The Elves play this game by taking turns arranging the marbles in a circle
 * according to very particular rules. The marbles are numbered starting with 0
 * and increasing by 1 until every marble has a number.
 *
 * First, the marble numbered 0 is placed in the circle. At this point, while
 * it contains only a single marble, it is still a circle: the marble is both
 * clockwise from itself and counter-clockwise from itself. This marble is
 * designated the current marble.
 *
 * Then, each Elf takes a turn placing the lowest-numbered remaining marble into
 * the circle between the marbles that are 1 and 2 marbles clockwise of the
 * current marble. (When the circle is large enough, this means that there is
 * one marble between the marble that was just placed and the current marble.)
 * The marble that was just placed then becomes the current marble.
 *
 * However, if the marble that is about to be placed has a number which is a
 * multiple of 23, something entirely different happens. First, the current
 * player keeps the marble they would have placed, adding it to their score.
 * In addition, the marble 7 marbles counter-clockwise from the current
 * marble is removed from the circle and also added to the current player's
 * score. The marble located immediately clockwise of the marble that was
 * removed becomes the new current marble.
 *
 * For example, suppose there are 9 players. After the marble with value 0 is
 * placed in the middle, each player (shown in square brackets) takes a turn.
 * The result of each of those turns would produce circles of marbles like
 * this, where clockwise is to the right and the resulting current marble
 * is in parentheses:
 *
 * [-] (0)
 * [1]  0 (1)
 * [2]  0 (2) 1
 * [3]  0  2  1 (3)
 * [4]  0 (4) 2  1  3
 * [5]  0  4  2 (5) 1  3
 * [6]  0  4  2  5  1 (6) 3
 * [7]  0  4  2  5  1  6  3 (7)
 * [8]  0 (8) 4  2  5  1  6  3  7
 * [9]  0  8  4 (9) 2  5  1  6  3  7
 * [1]  0  8  4  9  2(10) 5  1  6  3  7
 * [2]  0  8  4  9  2 10  5(11) 1  6  3  7
 * [3]  0  8  4  9  2 10  5 11  1(12) 6  3  7
 * [4]  0  8  4  9  2 10  5 11  1 12  6(13) 3  7
 * [5]  0  8  4  9  2 10  5 11  1 12  6 13  3(14) 7
 * [6]  0  8  4  9  2 10  5 11  1 12  6 13  3 14  7(15)
 * [7]  0(16) 8  4  9  2 10  5 11  1 12  6 13  3 14  7 15
 * [8]  0 16  8(17) 4  9  2 10  5 11  1 12  6 13  3 14  7 15
 * [9]  0 16  8 17  4(18) 9  2 10  5 11  1 12  6 13  3 14  7 15
 * [1]  0 16  8 17  4 18  9(19) 2 10  5 11  1 12  6 13  3 14  7 15
 * [2]  0 16  8 17  4 18  9 19  2(20)10  5 11  1 12  6 13  3 14  7 15
 * [3]  0 16  8 17  4 18  9 19  2 20 10(21) 5 11  1 12  6 13  3 14  7 15
 * [4]  0 16  8 17  4 18  9 19  2 20 10 21  5(22)11  1 12  6 13  3 14  7 15
 * [5]  0 16  8 17  4 18(19) 2 20 10 21  5 22 11  1 12  6 13  3 14  7 15
 * [6]  0 16  8 17  4 18 19  2(24)20 10 21  5 22 11  1 12  6 13  3 14  7 15
 * [7]  0 16  8 17  4 18 19  2 24 20(25)10 21  5 22 11  1 12  6 13  3 14  7 15
 *
 * The goal is to be the player with the highest score after the last marble is
 * used up. Assuming the example above ends after the marble numbered 25, the
 * winning score is 23+9=32 (because player 5 kept marble 23 and removed marble
 * 9, while no other player got any points in this very short example game).
 *
 * Here are a few more examples:
 *
 * 10 players; last marble is worth 1618 points: high score is 8317
 * 13 players; last marble is worth 7999 points: high score is 146373
 * 17 players; last marble is worth 1104 points: high score is 2764
 * 21 players; last marble is worth 6111 points: high score is 54718
 * 30 players; last marble is worth 5807 points: high score is 37305
 * What is the winning Elf's score?
 * */

// the number of elves playing each round
$player_count = 10;
//$player_count = 431;
$final_marble_value = 1618;
//$final_marble_value = 70950;

// an array to catch each elf-player's score
// should be of size $player_count + 1 (the zero'th move doesn't count as a player move,
// and the zero'th element of the array doesn't count as a player
$player_array = [];
$current_player = 0;

// an array to represent the board where each element is a board position and the value of
// the marble in that position
$board_array = [];
$current_board_position = 0;
$marble_value = 0;

// walk through the list of marbles until the $final_marble is set
for($x=0;$x<=$final_marble_value;++$x){
    // grab a new marble
    ++$marble_value;

    // determine whose turn it is
    if($current_player <= $player_count){
        ++$current_player;
    } else {
        $current_player = 1;
    }


    if($marble_value%23==0){ // is this a scoring round?
        $player_array[$current_player] += $marble_value;
        // find the board position -7 from $current_board_position, remove it, and add it to $player_array[$current_player's] score
        // move the $current_board_position to -6 it's current location
    } else {
        // insert into
    }
}

