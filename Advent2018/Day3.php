<?php
/**
 * Date: 12/4/2018
 * Time: 1:29 PM
 */

/*
 * --- Day 3: No Matter How You Slice It - Part 1 ---
 * The Elves managed to locate the chimney-squeeze prototype fabric for Santa's
 * suit (thanks to someone who helpfully wrote its box IDs on the wall of the
 * warehouse in the middle of the night). Unfortunately, anomalies are still
 * affecting them - nobody can even agree on how to cut the fabric.
 *
 * The whole piece of fabric they're working on is a very large square - at
 * least 1000 inches on each side.
 *
 * Each Elf has made a claim about which area of fabric would be ideal for
 * Santa's suit. All claims have an ID and consist of a single rectangle with
 * edges parallel to the edges of the fabric. Each claim's rectangle is defined
 * as follows:
 *
 *  * The number of inches between the left edge of the fabric and the left edge
 * of the rectangle.
 *  * The number of inches between the top edge of the fabric and the top edge
 * of the rectangle.
 *  * The width of the rectangle in inches.
 *  * The height of the rectangle in inches.
 *
 * A claim like #123 @ 3,2: 5x4 means that claim ID 123 specifies a rectangle
 * 3 inches from the left edge, 2 inches from the top edge, 5 inches wide, and
 * 4 inches tall. Visually, it claims the square inches of fabric represented
 * by # (and ignores the square inches of fabric represented by .) in the
 * diagram below:
 *
 * ...........
 * ...........
 * ...#####...
 * ...#####...
 * ...#####...
 * ...#####...
 * ...........
 * ...........
 * ...........
 *
 * The problem is that many of the claims overlap, causing two or more claims
 * to cover part of the same areas. For example, consider the following
 * claims:
 *
 * #1 @ 1,3: 4x4
 * #2 @ 3,1: 4x4
 * #3 @ 5,5: 2x2
 * Visually, these claim the following areas:
 *
 * ........
 * ...2222.
 * ...2222.
 * .11XX22.
 * .11XX22.
 * .111133.
 * .111133.
 * ........
 *
 * The four square inches marked with X are claimed by both 1 and 2. (Claim 3,
 * while adjacent to the others, does not overlap either of them.)
 *
 * If the Elves all proceed with their own plans, none of them will have enough
 * fabric. How many square inches of fabric are within two or more claims?
 * */

$problem_data = array(); /*an array of problem data provided by Advent of Code */
$cloth_map = array(); /* a 1000x1000 array that represents the cloth the elves want
 to bid on */
$cloth_map_value_sums = array(); /* an array that summarizes cloth map. keys
// are how many times the square is overlapped by a bid, values how many squares
// are overlapped this many times */

// grab the problem steps from the flat file
$handle = @fopen("c:\\Users\Bunny\Documents\git\Advent2018\day3data", "r");
if ($handle) {

    while (($line = fgets($handle, 4096)) !== false) {
        preg_match_all("/(\d)+/", $line, $matches);
        $problem_data [] = $matches[0];
    }

    if (!feof($handle)) {
        echo "Error: unexpected fgets() fail\n";
    }
    fclose($handle);

    // initialize the map of the cloth we'll apply our problem data to
    $cloth_map = array_fill(0, 1000, array_fill(0, 1000, 0));

    // for each elf bid
    foreach ($problem_data as $line) {
        // map out the corners of the bid
        $bidStartX = $line[1];
        $bidEndX = $bidStartX + $line[3] - 1;
        $bidStartY = $line[2];
        $bidEndY = $bidStartY + $line[4] - 1;

        // add one to each square on the cloth map that their bid would cover
        for ($x = $bidStartX; $x <= $bidEndX; $x++) {
            for ($y = $bidStartY; $y <= $bidEndY; $y++) {
                $cloth_map[$x][$y]++;
            }
        }

    }

    // count the squares in cloth_map by how many pieces touch each square
    foreach ($cloth_map as $row) {
        $row_totals = array_count_values($row);
        foreach ($row_totals as $key => $value) {
            if (key_exists($key, $cloth_map_value_sums)) {
                $cloth_map_value_sums[$key] += $value;
            } else {
                $cloth_map_value_sums[$key] = $value;
            }
        }
    }
}

/*
 * --- Part Two ---
 * Amidst the chaos, you notice that exactly one claim doesn't overlap by even a
 * single square inch of fabric with any other claim. If you can somehow draw
 * attention to it, maybe the Elves will be able to make Santa's suit after all!
 *
 * For example, in the claims above, only claim 3 is intact after all claims
 * are made.
 *
 * What is the ID of the only claim that doesn't overlap?
 * */

$problem_data2 = array(); /*an array of problem data provided by Advent of Code */
$cloth_map2 = array(); /* a 1000x1000 array that represents the cloth the elves want
 to bid on */
$problem_summary = array(); /*an array to catch the min value and layered
 value of each problem*/

$handle2 = @fopen("c:\\Users\Bunny\Documents\git\Advent2018\day3data", "r");

if ($handle2) {
    while (($line = fgets($handle2, 4096)) !== false) {
        preg_match_all("/(\d)+/", $line, $matches);
        $problem_summary[$matches[0][0]] = array(
            "ID" => (int)$matches[0][0],
            "startX" => (int)$matches[0][1],
            "endX" => (int)$matches[0][1] + (int)$matches[0][3] - 1,
            "width" => (int)$matches[0][3],
            "startY" => (int)$matches[0][2],
            "endY" => (int)$matches[0][2] + (int)$matches[0][4] - 1,
            "height" => (int)$matches[0][4],
            "area" => (int)$matches[0][3] * (int)$matches[0][4],
            "layered_val" => 0);
    }
    if (!feof($handle2)) {
        echo "Error: unexpected fgets() fail\n";
    }
    fclose($handle2);

    // initialize the map of the cloth we'll apply our problem data to
    $cloth_map2 = array_fill(0, 1000, array_fill(0, 1000, 0));

    // for each elf bid
    foreach ($problem_summary as $problem) {
        //add one to each square on the cloth map that their bid would cover
        for ($x = $problem['startX']; $x <= $problem['endX']; $x++) {
            for ($y = $problem['startY']; $y <= $problem['endY']; $y++) {
                $cloth_map2[$x][$y]++;
            }
        }
    }

    // total up the 'layered value' for each bid's area, which is the number of square
    // inches claimed in the bid's territory
    foreach ($problem_summary as $problem) {
        for ($x = $problem['startX']; $x <= $problem['endX']; $x++) {
            for ($y = $problem['startY']; $y <= $problem['endY']; $y++) {
                $problem_summary[$problem['ID']]['layered_val'] += $cloth_map2[$x][$y];
            }
        }
    }

    // compare the minimum value of each bid to it's 'layered' value in the cloth map
    // if they match, then the bid is uncontested
    foreach ($problem_summary as $key => $summary) {
        if (abs($summary['layered_val'] == $summary ['area'])) {
            echo $summary['ID'] . " is a match.\n";
        }
    }
}