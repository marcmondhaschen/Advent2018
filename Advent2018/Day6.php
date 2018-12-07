<?php
/**
 * Date: 12/5/2018
 * Time: 8:21 PM
 */

/*
 *--- Day 6: Chronal Coordinates ---
 * The device on your wrist beeps several times, and once again you feel like
 * you're falling.
 * "Situation critical," the device announces. "Destination indeterminate.
 *
 * Chronal interference detected. Please specify new target coordinates."
 *
 * The device then produces a list of coordinates (your puzzle input). Are they
 * places it thinks are safe or dangerous? It recommends you check manual page
 * 729. The Elves did not give you a manual.
 *
 * If they're dangerous, maybe you can minimize the danger by finding the co-
 * ordinate that gives the largest distance from the other points.
 *
 * Using only the Manhattan distance, determine the area around each coordinate
 * by counting the number of integer X,Y locations that are closest to that
 * coordinate (and aren't tied in distance to any other coordinate).
 *
 * Your goal is to find the size of the largest area that isn't infinite. For
 * example, consider the following list of coordinates:
 *
 * 1, 1
 * 1, 6
 * 8, 3
 * 3, 4
 * 5, 5
 * 8, 9
 *
 * If we name these coordinates A through F, we can draw them on a grid,
 * putting 0,0 at the top left:
 *
 * ..........
 * .A........
 * ..........
 * ........C.
 * ...D......
 * .....E....
 * .B........
 * ..........
 * ..........
 * ........F.
 *
 * This view is partial - the actual grid extends infinitely in all directions.
 * Using the Manhattan distance, each location's closest coordinate can be
 * determined, shown here in lowercase:
 *
 * aaaaa.cccc
 * aAaaa.cccc
 * aaaddecccc
 * aadddeccCc
 * ..dDdeeccc
 * bb.deEeecc
 * bBb.eeee..
 * bbb.eeefff
 * bbb.eeffff
 * bbb.ffffFf
 *
 * Locations shown as . are equally far from two or more coordinates, and so
 * they don't count as being closest to any.
 *
 * In this example, the areas of coordinates A, B, C, and F are infinite -
 * while not shown here, their areas extend forever outside the visible grid.
 * However, the areas of coordinates D and E are finite: D is closest to 9
 * locations, and E is closest to 17 (both including the coordinate's location
 * itself). Therefore, in this example, the size of the largest area is 17.
 *
 * What is the size of the largest area that isn't infinite?
 * */

$time_start = microtime(true);
$coordinates = array("x"=>0,"y"=>0,"area"=>0,"on_edge"=>FALSE);
$map = array("x"=>0,"y"=>0,"nearest_point"=>0,"edge"=>FALSE);

// ingest the puzzle input into an array of 2-element arrays whose keys are x and y
$data = file('day6data',FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
foreach ($data as $line) {
    preg_match_all('/(\d)+/', $line, $matches);
    $coordinates[] = array(
        "x"=>$matches[0][0],
        "y"=>$matches[0][1],
        "area"=>0,
        "on_edge"=>FALSE
    );
}

// determine size of our map from the coordinates supplied
$largestX = max(array_column($coordinates,'x'));
$smallestX = min(array_column($coordinates,'x'));
$largestY = max(array_column($coordinates,'y'));
$smallestY = min(array_column($coordinates,'y'));

// build an empty map of to size from lines above
for($x=$smallestX-1; $x<=$largestX+1; ++$x){
    for($y=$smallestY-1; $y<=$largestY+1; ++$y){
        $edge = false;
        if ($x==$largestX+1 || $x==$smallestX-1 || $y==$largestY+1 || $y==$smallestY-1){
            $edge = true;
        }
        $map[] = array(
            "x"=>$x,
            "y"=>$y,
            "nearest_point"=>nearest_point($x,$y,$coordinates),
            "edge"=>$edge
        );
    }
}

print_r(array_count_values(array_column($map,'nearest_point')));

function manhattan_distance($x1,$y1,$x2,$y2){
    return abs($x1-$x2)+abs($y1-$y2);
}

function nearest_point($x,$y,$coordinates){
    $coordinate_distance = array();
    foreach($coordinates as $key=>$value){
        echo "Key: " . $key . "\n";
        echo "CX: " . $value['x'] . " CY: " . $value['y'] . "\n";
        echo "LX: " . $x . "LY: " . $y . "\n";
        $coordinate_distance[$key] = manhattan_distance($x,$value['x'],$y,$value['y']);
    }
    $closest_point_distance = min($coordinate_distance);
    $nearest_point = array_search($closest_point_distance,$coordinate_distance);

    if(count(array_keys($coordinate_distance, $closest_point_distance))==1){
        return $nearest_point;
    } else {
        return PHP_INT_MAX;
    }
}

//echo "Run time(seconds): " . (microtime(true) - $time_start) . "\n";