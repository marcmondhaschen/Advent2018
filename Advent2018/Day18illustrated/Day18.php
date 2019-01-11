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

// count the lumberyards (#) and tree (|) acres around a given acre
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

// make a function to change an acre from a grid
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

// advance the entire grid by one turn
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

// display the grid
function printGrid($data)
{
    foreach ($data as $row) {
        foreach ($row as $cell) {
            echo $cell;
        }
        echo PHP_EOL;
    }
}

// total the lumberyards (#) by the number of trees (|) for a given grid state
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