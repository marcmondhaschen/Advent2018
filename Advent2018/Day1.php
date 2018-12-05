<?php

/**
 * Date: 11/30/2018
 * Time: 10:53 PM
 */
/*
 * --- Day 1: Chronal Calibration ---
 * "We've detected some temporal anomalies," one of Santa's Elves at the
 * Temporal Anomaly Research and Detection Instrument Station tells you.
 * She sounded pretty worried when she called you down here. "At 500-year
 * intervals into the past, someone has been changing Santa's history!"
 *
 * "The good news is that the changes won't propagate to our time stream
 * for another 25 days, and we have a device" - she attaches something to
 * your wrist - "that will let you fix the changes with no such propagation
 * delay. It's configured to send you 500 years further into the past every
 * few days; that was the best we could do on such short notice."
 *
 * "The bad news is that we are detecting roughly fifty anomalies throughout
 * time; the device will indicate fixed anomalies with stars. The other bad
 * news is that we only have one device and you're the best person for the
 * job! Good lu--" She taps a button on the device and you suddenly feel like
 * you're falling. To save Christmas, you need to get all fifty stars by
 * December 25th.
 *
 * Collect stars by solving puzzles. Two puzzles will be made available on each
 * day in the advent calendar; the second puzzle is unlocked when you complete
 * the first. Each puzzle grants one star. Good luck!
 *
 * After feeling like you've been falling for a few minutes, you look at the
 * device's tiny screen. "Error: Device must be calibrated before first use.
 * Frequency drift detected. Cannot maintain destination lock." Below the
 * message, the device shows a sequence of changes in frequency (your puzzle
 * input). A value like +6 means the current frequency increases by 6; a value
 * like -3 means the current frequency decreases by 3.
 *
 * For example, if the device displays frequency changes of +1, -2, +3, +1,
 * then starting from a frequency of zero, the following changes would occur:
 *
 * Current frequency  0, change of +1; resulting frequency  1
 * Current frequency  1, change of -2; resulting frequency -1
 * Current frequency -1, change of +3; resulting frequency  2
 * Current frequency  2, change of +1; resulting frequency  3
 *
 * In this example, the resulting frequency is 3.
 *
 * Here are other example situations:
 *
 * +1, +1, +1 results in  3
 * +1, +1, -2 results in  0
 * -1, -2, -3 results in -6
 *
 * Starting with a frequency of zero, what is the resulting frequency after
 * all of the changes in frequency have been applied?
*/

$handle = @fopen("c:\\Users\Bunny\Documents\git\Advent2018\day1data", "r");

if ($handle) {
    $total = 0;
    while (($buffer = fgets($handle, 4096)) !== false) {
        $total += $buffer;
    }
    if (!feof($handle)) {
        echo "Error: unexpected fgets() fail\n";
    }
    fclose($handle);
    echo "The answer to part 1 is: " . $total . "\n";
}


/*
 * --- Part Two ---
 * You notice that the device repeats the same frequency change list over and
 * over. To calibrate the device, you need to find the first frequency it
 * reaches twice.
 *
 * For example, using the same list of changes above, the device would loop
 * as follows:
 *
 * Current frequency  0, change of +1; resulting frequency  1
 * Current frequency  1, change of -2; resulting frequency -1
 * Current frequency -1, change of +3; resulting frequency  2
 * Current frequency  2, change of +1; resulting frequency  3
 *
 * (At this point, the device continues from the start of the list.)
 * Current frequency  3, change of +1; resulting frequency  4.
 * Current frequency  4, change of -2; resulting frequency  2, which has already
 * been seen.
 *
 * In this example, the first frequency reached twice is 2. Note that your
 * device might need to repeat its list of frequency changes many times before
 * a duplicate frequency is found, and that duplicates might be found while in
 * the middle of processing the list.
 *
 * Here are other examples:
 *
 * +1, -1 first reaches 0 twice.
 * +3, +3, +4, -2, -4 first reaches 10 twice.
 * -6, +3, +8, +5, -6 first reaches 5 twice.
 * +7, +7, -2, -7, -4 first reaches 14 twice.
 *
 * What is the first frequency your device reaches twice?
 */

$handle2 = @fopen("c:\\Users\Bunny\Documents\git\Advent2018\day1data", "r");

if ($handle2) { //assuming we can open the data file...
    $change_array = array(); // an array made from the input file's lines
    $frequency_array = array(); // an array of all the frequencies hit in the first
                                // pass of through the list of changes
    $line_total = 0; // the total amount change created by summing change_array once
    $change_count = 0; // the count of lines/changes in change_array
    $answers = array();

    $min_index = 0;
    $min_diff = 0;
    $min_freq = 0;

    // grab all the changes from the flat file
    while (($line = fgets($handle2, 4096)) !== false) {
        $change_array[] = $line;
    }
    if (!feof($handle2)) {
        echo "Error: unexpected fgets() fail\n";
    }
    fclose($handle2);

    
    // get a count of n
    $change_array_length = count($change_array);

    // get the frequency change of one complete iteration through the list
    foreach($change_array as $change) {
        $frequency_array[] = $line_total;
        $line_total += $change;
    }
    
    // first edge case - if the total change from the sum of all changes is zero
    // then the answer is zero
    if ($line_total == 0){
        $answers[]= 0;
        return $answers;
    }
    
    // second edge case - see if repetition happens within the first iteration
    if(count(array_count_values($frequency_array))){
        $answers[] = array_filter($frequency_array, function ($value) {return ($value > 1);});
        return $answers;
    }

    // general case - use the frequency_array to calculate the min_index,
    // min_diff and min_freq
}


//from collections import defaultdict
//from itertools import accumulate

// # assume that some external code has passed in the string lines of the input puzzle.
// def solve(lines):
//    data = [int(line) for line in lines]

//    # calculate the cumulative sums
//    sums = [0] + list(accumulate(data))

//    # check if the repetition occurs in the first iteration
//    sum_set = set()
//    for s in sums:
//        if s in sum_set:
//            return s
//        sum_set.add(s)

//    # find the final sum after performing one iteration
//    final_sum = sums[-1]
//    if final_sum == 0:
//        return 0  # if the shift is 0, then the first repetition is 0

//    sums = sums[:-1]  # Remove the last element as it belongs to iteration 2, not iteration 1.

//    # populate a dictionary of all the groups where the value is the list of frequencies in the group
//    groups = defaultdict(list)
//    for i, s in enumerate(sums):
//        groups[s % final_sum].append((i, s))  # each value will be a tuple of the index and the frequency

//    # find the minimum difference frequencies
//    min_index, min_diff, min_freq = None, None, None

//    for group in groups.values():
//        # sort by frequency
//        sorted_vals = list(sorted(group, key=lambda t: t[1]))
//        for i in range(1, len(sorted_vals)):
//            # calculate the difference and the index of the repetition inside the list of frequencies
//            diff = sorted_vals[i][1] - sorted_vals[i - 1][1]
//            index = sorted_vals[i-1][0] if final_sum > 0 else sorted_vals[i][0]
//            freq = sorted_vals[i][1] if final_sum > 0 else sorted_vals[i-1][1]
//            if min_diff is None or diff < min_diff or (diff == min_diff and index < min_index):
//                min_index = index
//                min_diff = diff
//                min_freq = freq
//
//    return min_freq
