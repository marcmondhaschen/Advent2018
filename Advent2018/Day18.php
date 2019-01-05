<?php
/**
 * User: Marc
 * Date: 12/28/18
 * Time: 12:47 PM
 */

// grab the problem data and put it in a compound array
$data = [];
$handle = fopen('day18data', 'r');
if ($handle) {
    while (!feof($handle)) {
        $data[] = str_split(fgets($handle));
    }
    fclose($handle);
}

$changedData = changeGridOverTime($data, 1000);

$yardTotals = yardTotal($changedData);

echo "There are " . $yardTotals['trees'] . " trees and " . $yardTotals['yards'] . " yards" . PHP_EOL;
echo "for a total score of " . $yardTotals['trees'] * $yardTotals['yards'] . PHP_EOL;

// make a function to count the lumberyards (#) and tree (|) acres around a given acre
function countOf($x, $y, $data)
{
    $counts = ["trees" => "0", "yards" => "0"];
    for ($k = $y - 1; $k <= $y + 1; ++$k) {
        for ($j = $x - 1; $j <= $x + 1; ++$j) {
            if (isset($data[$j][$k])) {
                if (!($j == $x && $k == $y)) {
                    if ($data[$k][$j] == "|") {
                        ++$counts['trees'];
                    } else if ($data[$k][$j] == "#") {
                        ++$counts['yards'];
                    }
                }
            }
        }
    }
    return $counts;
}

// make a function to change acreage each turn
function changeAcre($x, $y, $data)
{
    $state = $data[$y][$x];
    if ($state == ".") {
        if (countOf($x, $y, $data)['trees'] > 2) {
            $state = "|";
        }
    } elseif ($state == "|") {
        if (countOf($x, $y, $data)['yards'] > 2) {
            $state = "#";
        }
    } elseif ($state == "#") {
        if (!(countOf($x, $y, $data)['trees'] > 0 && countOf($x, $y, $data)['yards'] > 0)) {
            $state = ".";
        }
    }
    return $state;
}

function changeGridOverTime($data, $minutes)
{
    $boardState = $data;
    for ($x = 0; $x < $minutes; ++$x) {
        $boardState = changeGrid($boardState);
        $yardTotals = yardTotal($boardState);
        echo $x . ", " . $yardTotals['trees'] * $yardTotals['yards'] . PHP_EOL;
    }
    return $boardState;
}

// make a function to advance the entire grid by one turn
function changeGrid($data)
{
    $newData = [];
    foreach ($data as $y => $row) {
        foreach ($row as $x => $value) {
            $newData[$y][$x] = changeAcre($x, $y, $data);
        }
    }
    return $newData;
}

// make a function to print the grid in an easily comparable state
function printGrid($data)
{
    foreach ($data as $row) {
        foreach ($row as $cell) {
            echo $cell;
        }
        echo PHP_EOL;
    }
}

// make a function to total the lumberyards (#) by the number of trees (|) for a given grid state
function yardTotal($data)
{
    $counts = ["trees" => "0",
        "yards" => "0"];
    foreach ($data as $row) {
        foreach ($row as $cell) {
            if ($cell == "|") {
                ++$counts['trees'];
            } elseif ($cell == "#") {
                ++$counts['yards'];
            }
        }
    }
    return $counts;
}


/*
 * --- Day 18: Settlers of The North Pole ---
On the outskirts of the North Pole base construction project, many Elves
are collecting lumber.

The lumber collection area is 50 acres by 50 acres; each acre can be either
open ground (.), trees (|), or a lumberyard (#). You take a scan of the area
(your puzzle input).

Strange magic is at work here: each minute, the landscape looks entirely
different. In exactly one minute, an open acre can fill with trees, a wooded
acre can be converted to a lumberyard, or a lumberyard can be cleared to open
ground (the lumber having been sent to other projects).

The change to each acre is based entirely on the contents of that acre as
well as the number of open, wooded, or lumberyard acres adjacent to it at
the start of each minute. Here, "adjacent" means any of the eight acres
surrounding that acre. (Acres on the edges of the lumber collection area
might have fewer than eight adjacent acres; the missing acres aren't
counted.)

In particular:

An open acre will become filled with trees if three or more adjacent acres
contained trees. Otherwise, nothing happens.

An acre filled with trees will become a lumberyard if three or more adjacent
acres were lumberyards. Otherwise, nothing happens.

An acre containing a lumberyard will remain a lumberyard if it was adjacent
to at least one other lumberyard and at least one acre containing trees.
Otherwise, it becomes open.

These changes happen across all acres simultaneously, each of them using the
state of all acres at the beginning of the minute and changing to their new
form by the end of that same minute. Changes that happen during the minute
don't affect each other.

For example, suppose the lumber collection area is instead only 10 by 10
acres with this initial configuration:

Initial state:
.#.#...|#.
.....#|##|
.|..|...#.
..|#.....#
#.#|||#|#|
...#.||...
.|....|...
||...#|.#|
|.||||..|.
...#.|..|.

After 1 minute:
.......##.
......|###
.|..|...#.
..|#||...#
..##||.|#|
...#||||..
||...|||..
|||||.||.|
||||||||||
....||..|.

After 2 minutes:
.......#..
......|#..
.|.|||....
..##|||..#
..###|||#|
...#|||||.
|||||||||.
||||||||||
||||||||||
.|||||||||

After 3 minutes:
.......#..
....|||#..
.|.||||...
..###|||.#
...##|||#|
.||##|||||
||||||||||
||||||||||
||||||||||
||||||||||

After 4 minutes:
.....|.#..
...||||#..
.|.#||||..
..###||||#
...###||#|
|||##|||||
||||||||||
||||||||||
||||||||||
||||||||||

After 5 minutes:
....|||#..
...||||#..
.|.##||||.
..####|||#
.|.###||#|
|||###||||
||||||||||
||||||||||
||||||||||
||||||||||

After 6 minutes:
...||||#..
...||||#..
.|.###|||.
..#.##|||#
|||#.##|#|
|||###||||
||||#|||||
||||||||||
||||||||||
||||||||||

After 7 minutes:
...||||#..
..||#|##..
.|.####||.
||#..##||#
||##.##|#|
|||####|||
|||###||||
||||||||||
||||||||||
||||||||||

After 8 minutes:
..||||##..
..|#####..
|||#####|.
||#...##|#
||##..###|
||##.###||
|||####|||
||||#|||||
||||||||||
||||||||||

After 9 minutes:
..||###...
.||#####..
||##...##.
||#....###
|##....##|
||##..###|
||######||
|||###||||
||||||||||
||||||||||

After 10 minutes:
.||##.....
||###.....
||##......
|##.....##
|##.....##
|##....##|
||##.####|
||#####|||
||||#|||||
||||||||||
After 10 minutes, there are 37 wooded acres and 31 lumberyards. Multiplying
the number of wooded acres by the number of lumberyards gives the total resource
value after ten minutes: 37 * 31 = 1147.

What will the total resource value of the lumber collection area be after 10 minutes?
 * */