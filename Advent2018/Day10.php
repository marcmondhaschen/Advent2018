<?php
/**
 * Date: 12/11/2018
 * Time: 12:37 PM
 */

/*
 * --- Day 10: The Stars Align ---
 * It's no use; your navigation system simply isn't capable of providing
 * walking directions in the arctic circle, and certainly not in 1018.
 *
 * The Elves suggest an alternative. In times like these, North Pole rescue
 * operations will arrange points of light in the sky to guide missing Elves
 * back to base. Unfortunately, the message is easy to miss: the points move
 * slowly enough that it takes hours to align them, but have so much momentum
 * that they only stay aligned for a second. If you blink at the wrong time,
 * it might be hours before another message appears.
 *
 * You can see these points of light floating in the distance, and record
 * their position in the sky and their velocity, the relative change in
 * position per second (your puzzle input). The coordinates are all given from
 * your perspective; given enough time, those positions and velocities will
 * move the points into a cohesive message!
 *
 * Rather than wait, you decide to fast-forward the process and calculate what
 * the points will eventually spell.
 *
 * For example, suppose you note the following points:
 *
 * position=< 9,  1> velocity=< 0,  2>
 * position=< 7,  0> velocity=<-1,  0>
 * position=< 3, -2> velocity=<-1,  1>
 * position=< 6, 10> velocity=<-2, -1>
 * position=< 2, -4> velocity=< 2,  2>
 * position=<-6, 10> velocity=< 2, -2>
 * position=< 1,  8> velocity=< 1, -1>
 * position=< 1,  7> velocity=< 1,  0>
 * position=<-3, 11> velocity=< 1, -2>
 * position=< 7,  6> velocity=<-1, -1>
 * position=<-2,  3> velocity=< 1,  0>
 * position=<-4,  3> velocity=< 2,  0>
 * position=<10, -3> velocity=<-1,  1>
 * position=< 5, 11> velocity=< 1, -2>
 * position=< 4,  7> velocity=< 0, -1>
 * position=< 8, -2> velocity=< 0,  1>
 * position=<15,  0> velocity=<-2,  0>
 * position=< 1,  6> velocity=< 1,  0>
 * position=< 8,  9> velocity=< 0, -1>
 * position=< 3,  3> velocity=<-1,  1>
 * position=< 0,  5> velocity=< 0, -1>
 * position=<-2,  2> velocity=< 2,  0>
 * position=< 5, -2> velocity=< 1,  2>
 * position=< 1,  4> velocity=< 2,  1>
 * position=<-2,  7> velocity=< 2, -2>
 * position=< 3,  6> velocity=<-1, -1>
 * position=< 5,  0> velocity=< 1,  0>
 * position=<-6,  0> velocity=< 2,  0>
 * position=< 5,  9> velocity=< 1, -2>
 * position=<14,  7> velocity=<-2,  0>
 * position=<-3,  6> velocity=< 2, -1>
 *
 * Each line represents one point. Positions are given as <X, Y> pairs: X
 * represents how far left (negative) or right (positive) the point appears,
 * while Y represents how far up (negative) or down (positive) the point appears.
 *
 * At 0 seconds, each point has the position given. Each second, each point's
 * velocity is added to its position. So, a point with velocity <1, -2> is
 * moving to the right, but is moving upward twice as quickly. If this point's
 * initial position were <3, 9>, after 3 seconds, its position would
 * become <6, 3>.
 *
 * Over time, the points listed above would move like this:
 *
 * Initially:
 * ........#.............
 * ................#.....
 * .........#.#..#.......
 * ......................
 * #..........#.#.......#
 * ...............#......
 * ....#.................
 * ..#.#....#............
 * .......#..............
 * ......#...............
 * ...#...#.#...#........
 * ....#..#..#.........#.
 * .......#..............
 * ...........#..#.......
 * #...........#.........
 * ...#.......#..........
 *
 * After 1 second:
 * ......................
 * ......................
 * ..........#....#......
 * ........#.....#.......
 * ..#.........#......#..
 * ......................
 * ......#...............
 * ....##.........#......
 * ......#.#.............
 * .....##.##..#.........
 * ........#.#...........
 * ........#...#.....#...
 * ..#...........#.......
 * ....#.....#.#.........
 * ......................
 * ......................
 *
 * After 2 seconds:
 * ......................
 * ......................
 * ......................
 * ..............#.......
 * ....#..#...####..#....
 * ......................
 * ........#....#........
 * ......#.#.............
 * .......#...#..........
 * .......#..#..#.#......
 * ....#....#.#..........
 * .....#...#...##.#.....
 * ........#.............
 * ......................
 * ......................
 * ......................
 *
 * After 3 seconds:
 * ......................
 * ......................
 * ......................
 * ......................
 * ......#...#..###......
 * ......#...#...#.......
 * ......#...#...#.......
 * ......#####...#.......
 * ......#...#...#.......
 * ......#...#...#.......
 * ......#...#...#.......
 * ......#...#..###......
 * ......................
 * ......................
 * ......................
 * ......................
 *
 * After 4 seconds:
 * ......................
 * ......................
 * ......................
 * ............#.........
 * ........##...#.#......
 * ......#.....#..#......
 * .....#..##.##.#.......
 * .......##.#....#......
 * ...........#....#.....
 * ..............#.......
 * ....#......#...#......
 * .....#.....##.........
 * ...............#......
 * ...............#......
 * ......................
 * ......................
 * After 3 seconds, the message appeared briefly: HI. Of course, your message
 * will be much longer and will take many more seconds to appear.
 *
 * What message will eventually appear in the sky?
 *
 *  */

// * start the run timer for the first part
$time_start = microtime(true);

// bunch of variables
$puzzle_data_raw = [];
$puzzle_data = $puzzle_data2 = [];
$area_current = $area_last = PHP_INT_MAX;
$seconds_passed = 0;
$seconds_min_area = 0;
$dimensions_last = $dimensions_current = [];
$display_data = [];

// capture puzzle data
$puzzle_data_raw = file('day10data');
foreach($puzzle_data_raw as $row) {
    preg_match_all('/(-?\d+)/',$row,$matches);
    $puzzle_data[] = $puzzle_data2[] =$matches[0];
}

// find the second at which the set is transformed to its smallest size
while($area_current<=$area_last){
    ++$seconds_passed;
    $area_last = $area_current;
    $dimensions_last = $dimensions_current;
    $dimensions_current = one_second_summary($puzzle_data, $seconds_passed);
    $area_current = $dimensions_current['minArea'];
};
$seconds_min_area = $seconds_passed - 1;

// build a new puzzle_data set which is run forward to the moment at which it's smallest
$smallest_puzzle_data = one_second_transform($puzzle_data, $seconds_min_area);

// build an empty display board
$xDimension = $dimensions_last['maxX']-$dimensions_last['minX'];
$yDimension = $dimensions_last['maxY']-$dimensions_last['minY'];
$display_data = array_fill(0,$xDimension,array_fill(0,$yDimension,0));

// update the display board with puzzle data
//foreach($smallest_puzzle_data as $row){
//    $display_data[$row[0]][$row[1]] = 1;
//}
// print_r($dimensions_last); // x is 0-9, y is 0-7
// print_r($smallest_puzzle_data); // x is 0-9, y is 0-7


function one_second_summary($puzzle_data,$seconds_passed){
    $temp_puzzle_data = one_second_transform($puzzle_data, $seconds_passed);

    $minX = min(array_column($temp_puzzle_data,0));
    $maxX = max(array_column($temp_puzzle_data,0));
    $minY = min(array_column($temp_puzzle_data,1));
    $maxY = max(array_column($temp_puzzle_data,1));
    $minArea = abs($minX - $maxX) * abs($minY - $maxY);

    return array(
        'minX' => $minX,
        'maxX' => $maxX,
        'minY' => $minY,
        'maxY' => $maxY,
        'minArea'=> $minArea
    );
}

function one_second_transform($puzzle_data, $seconds_passed){
    $temp_puzzle_data = $puzzle_data;
    foreach($temp_puzzle_data as $key=>$line) {
        $temp_puzzle_data[$key][0] += $line[2] * $seconds_passed;
        $temp_puzzle_data[$key][1] += $line[3] * $seconds_passed;
    }
    return $temp_puzzle_data;
}

// * stop the run timer for the first part
echo "Runtime 1 (seconds): " . (microtime(true) - $time_start) . "\n";

