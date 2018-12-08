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

// start run timer
$time_start = microtime(true);

// build an array to catch coordinates from the problem setup
$coordinates = [];

// build an array to represent the map the coordinates will fall onto
$map = [];

// ingest the puzzle input into an array of 2-element arrays whose keys are x and y
$data = file('day6data',FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
foreach ($data as $line) {
    preg_match_all('/(\d)+/', $line, $matches);
    $coordinates[] = array(
        "x"=>$matches[0][0],
        "y"=>$matches[0][1],
        "area"=>0,
        "on_edge"=>FALSE);
}

// populate the map
for($x=0; $x<1000; ++$x){
    for($y=0; $y<1000; ++$y){
        if ($x===0 || $x===999 || $y===0 || $y===999){
            $edge = TRUE;
        } else {
            $edge = FALSE;
        }
        $map[] = array(
            "x"=>$x,
            "y"=>$y,
            "nearest_point"=> nearest_point($x,$y,$coordinates),
            "edge"=>$edge);
    }
}

// measure coordinate areas
coordinate_area($coordinates, $map);

// disqualify edge coordinates
coordinate_edges($coordinates, $map);

// show size of largest, non-infinite area
$max_confined_area = 0;
foreach($coordinates as $coordinate){
    if(!$coordinate['on_edge'] && $coordinate['area']>$max_confined_area){
        $max_confined_area = $coordinate['area'];
    }
}
echo "The largest confined area is " . $max_confined_area . "\n";

// stop run timer
echo "\nFirst part run time (seconds): " . (microtime(true) - $time_start) . "\n";


/*
 * --- Part Two ---
 * On the other hand, if the coordinates are safe, maybe the best you can do is
 * try to find a region near as many coordinates as possible.
 *
 * For example, suppose you want the sum of the Manhattan distance to all of the
 * coordinates to be less than 32. For each location, add up the distances to
 * all of the given coordinates; if the total of those distances is less than
 * 32, that location is within the desired region. Using the same coordinates
 * as above, the resulting region looks like this:
 *
 * ..........
 * .A........
 * ..........
 * ...###..C.
 * ..#D###...
 * ..###E#...
 * .B.###....
 * ..........
 * ..........
 * ........F.
 *
 * In particular, consider the highlighted location 4,3 located at the top
 * middle of the region. Its calculation is as follows, where abs() is the
 * absolute value function:
 *
 * Distance to coordinate A: abs(4-1) + abs(3-1) =  5
 * Distance to coordinate B: abs(4-1) + abs(3-6) =  6
 * Distance to coordinate C: abs(4-8) + abs(3-3) =  4
 * Distance to coordinate D: abs(4-3) + abs(3-4) =  2
 * Distance to coordinate E: abs(4-5) + abs(3-5) =  3
 * Distance to coordinate F: abs(4-8) + abs(3-9) = 10
 * Total distance: 5 + 6 + 4 + 2 + 3 + 10 = 30
 *
 * Because the total distance to all coordinates (30) is less than 32, the
 * location is within the region.
 *
 * This region, which also includes coordinates D and E, has a total size of 16.
 *
 * Your actual region will need to be much larger than this example, though,
 * instead including all locations with a total distance of less than 10000.
 *
 * What is the size of the region containing all locations which have a total
 * distance to all given coordinates of less than 10000?
*/



// start run timer
$time_start2 = microtime(true);

// build an array to catch coordinates from the problem setup
$coordinates = [];

// build an array to represent the map the coordinates will fall onto
$map = [];

// build a catch variable to count the safe areas of the map
$cumulative_safe_area = 0;

// ingest the puzzle input into an array of 2-element arrays whose keys are x and y
$data = file('day6data',FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
foreach ($data as $line) {
    preg_match_all('/(\d)+/', $line, $matches);
    $coordinates[] = array(
        "x"=>$matches[0][0],
        "y"=>$matches[0][1]);
}


// populate the map
for($x=0; $x<1000; ++$x){
    for($y=0; $y<1000; ++$y){
        $map[] = array(
            "x"=>$x,
            "y"=>$y,
            "safe"=>safe_area($x, $y, $coordinates, 10000));
    }
}


// measure safe_area
foreach($map as $point){
    if($point['safe']){
        ++$cumulative_safe_area ;
    }
}
echo "The safe area is of size: " . $cumulative_safe_area  . "\n";


// stop run timer
echo "\nSecond part run time (seconds): " . (microtime(true) - $time_start) . "\n";
/*
*/


// a function to sum up a coordinate's 'unchallenged' area
function coordinate_area(&$coordinates, $map){
    foreach($map as $point){
        $nearest_point = $point['nearest_point'];
        if($nearest_point != PHP_INT_MAX) {
            $coordinates[$point['nearest_point']]['area']++;
        }
    }
}


// need to flag edge/not edge
function coordinate_edges(&$coordinates, $map){
    foreach($map as $point){
        if($coordinates[$point['nearest_point']]['on_edge']==FALSE && $point['edge']==TRUE){
            $coordinates[$point['nearest_point']]['on_edge'] = TRUE;
        }
    }
}


// a function that returns the manhattan distance between two points ([x1, y1] & [x2, y2])
function manhattan_distance($x1,$y1,$x2,$y2){
    return abs($x1-$x2)+abs($y1-$y2);
}


// a function to find the closest coordinate from an array of coordinates to a given point
// returns the nearest point on the coordinates list, by index, to the x/y supplied
function nearest_point($x,$y,$coordinates){
    $coordinate_distance = [];
    foreach($coordinates as $key=>$coordinate){
        $coordinate_distance[$key] = manhattan_distance($x,$y,$coordinate['x'],$coordinate['y']);
    }
    $closest_coord_distance = min($coordinate_distance);
    $nearest_coord = array_search($closest_coord_distance,$coordinate_distance);
    if(count(array_keys($coordinate_distance, $closest_coord_distance))==1){
        return $nearest_coord;
    } else {
        return PHP_INT_MAX;
    }
}


// a function to check if a map point is inside the safe max distance from all coordinates
// returns true/false
function safe_area($x, $y, $coordinates, $max_distance){
    $cumulative_distance = 0;
    foreach($coordinates as $coordinate){
        $cumulative_distance += manhattan_distance($x, $y, $coordinate['x'], $coordinate['y']);
    }
    if($cumulative_distance<$max_distance){
        return 1;
    } else {
        return 0;
    }
}