<?php
/**
 * Author: Mattie112
 * Date: 12/7/18
 * Time: 5:16 PM
 */

/*
 * --- Day 7: The Sum of Its Parts ---
 * You find yourself standing on a snow-covered coastline; apparently, you
 * landed a little off course. The region is too hilly to see the North Pole
 * from here, but you do spot some Elves that seem to be trying to unpack
 * something that washed ashore. It's quite cold out, so you decide to risk
 * creating a paradox by asking them for directions.
 *
 * "Oh, are you the search party?" Somehow, you can understand whatever Elves
 * from the year 1018 speak; you assume it's Ancient Nordic Elvish. Could the
 * device on your wrist also be a translator? "Those clothes don't look very
 * warm; take this." They hand you a heavy coat.
 *
 * "We do need to find our way back to the North Pole, but we have higher
 * priorities at the moment. You see, believe it or not, this box contains
 * something that will solve all of Santa's transportation problems - at least,
 * that's what it looks like from the pictures in the instructions." It doesn't
 * seem like they can read whatever language it's in, but you can: "Sleigh kit.
 * Some assembly required."
 *
 * "'Sleigh'? What a wonderful name! You must help us assemble this 'sleigh'
 * at once!" They start excitedly pulling more parts out of the box.
 *
 * The instructions specify a series of steps and requirements about which
 * steps must be finished before others can begin (your puzzle input). Each
 * step is designated by a single letter. For example, suppose you have the
 * following instructions:
 *
 * Step C must be finished before step A can begin.
 * Step C must be finished before step F can begin.
 * Step A must be finished before step B can begin.
 * Step A must be finished before step D can begin.
 * Step B must be finished before step E can begin.
 * Step D must be finished before step E can begin.
 * Step F must be finished before step E can begin.
 *
 * Visually, these requirements look like this:
 *
 *
 *   -->A--->B--
 *  /    \      \
 * C      -->D----->E
 *  \           /
 *   ---->F-----
 *
 * Your first goal is to determine the order in which the steps should be
 * completed. If more than one step is ready, choose the step which is first
 * alphabetically. In this example, the steps would be completed as follows:
 *
 * Only C is available, and so it is done first.
 * Next, both A and F are available. A is first alphabetically, so it is done next.
 * Then, even though F was available earlier, steps B and D are now also available, and B is the first alphabetically of the three.
 * After that, only D and F are available. E is not available because only some of its prerequisites are complete. Therefore, D is completed next.
 * F is the only choice, so it is done next.
 * Finally, E is completed.
 * So, in this example, the correct order is CABDFE.
 *
 * In what order should the steps in your instructions be completed?
 * */

// start run timer
$time_start = microtime(true);

// the order in which steps should be executed for part one
$step_order = "";

// a tree of dependent steps from the problem data
$step_tree = [];

// a list of steps that don't currently have any unexecuted dependent steps
$open_step = [];

// ingest problem data into a tree of steps where the first array layer is the step
// and its values are dependencies.
if ($file = fopen(__DIR__ . "/day7data", "rb")) {
    while (!feof($file)) {
        $line = trim(fgets($file));
        if (preg_match("@Step (.) .* step (.)@", $line, $matches)) {
            $parent = $matches[1];
            $child = $matches[2];
            $step_tree[$parent][] = $child;
        }
    }
    fclose($file);
}

// Find parents that are not anyones children
while (count($step_tree) > 0) {

    foreach ($step_tree as $parent => $children) {
        $is_child = false;
        foreach ($step_tree as $subparent => $subchildren) {
            if ($subparent === $parent) {
                continue;
            }
            foreach ($subchildren as $subchild) {
                if ($subchild === $parent) {
                    $is_child = true;
                    break;
                }
            }
        }

        if (!$is_child) {
            if (!in_array($parent, $open_step, true)) {
                $open_step[] = $parent;
            }
        }
    }

    // Sort steps we could execute this turn alphabetically
    sort($open_step);

    $this_child = reset($open_step);
    $step_order .= $this_child;
// If this is the last element make sure to add all children
    if (count($step_tree) === 1) {
        foreach (reset($step_tree) as $item) {
            $step_order .= $item;
        }
    }

    unset($open_step[0]);
    unset($step_tree[$this_child]);
}

// Publish answer for the user
echo "The first part goes in this order: " . $step_order . "\n";

// stop run timer
echo "First part run time (seconds): " . (microtime(true) - $time_start) . "\n";




// start second run timer
$time_start2 = microtime(true);

// For part #2
$letters = array_merge([0 => 0], range('A', 'Z'));
$letters = array_flip($letters);
unset($letters[0]);
// Setup part #2
$letter_time = 60;
$worker_amount = 5;
if ($file = fopen(__DIR__ . "/day7-input.txt", "rb")) {
    while (!feof($file)) {
        $line = trim(fgets($file));
        if (preg_match("@Step (.) .* step (.)@", $line, $matches)) {
            $parent = $matches[1];
            $child = $matches[2];
            $step_tree[$parent][] = $child;
        }
    }
    fclose($file);
}
// False = idle
// True = working
// int = seconds busy
$workers = [];
for ($i = 1; $i <= $worker_amount; $i++) {
    $workers[$i] = [false, 0, ""];
}
$total_seconds = 0;
$todo = [];
$in_progress = [];

// Find parents that are not anyones children
while (count($step_tree) > 0) {
    // Only count the time once no matter how many workers
    $counted = false;
    foreach ($workers as $id => $elem) {
        if ($workers[$id][0] === true) {
            if (!$counted) {
                // Only add work time for a single worker as it does not matter how many are working in paralel
                $total_seconds++;
                $counted = true;
            }
            $workers[$id][1]--;
            if ($workers[$id][1] === 0) {
                $workers[$id][0] = false;
                echo "Worker " . $id . " is free again (finished " . $workers[$id][2] . ")" . PHP_EOL;
                $step_order .= $workers[$id][2];
                // When done don't forget to wait for the last job to finish
                if (count($step_tree) === 1) {
                    foreach (reset($step_tree) as $item) {
                        $step_order .= $item;
                        $total_seconds += $letter_time + $letters[$item];
                    }
                }
                echo $step_order . PHP_EOL;
                unset($step_tree[$workers[$id][2]]);
            }
        }
    }
    // Only continue when there are free workers
    $free_worker = false;
    foreach ($workers as $id => [$status, $seconds, $letter]) {
        if ($status === false) {
            $free_worker = true;
            break;
        }
    }
    if (!$free_worker) {
        continue;
    }
    $not_children = [];
    foreach ($step_tree as $parent => $children) {
        $is_child = false;
        foreach ($step_tree as $subparent => $subchildren) {
            if ($subparent === $parent) {
                continue;
            }
            foreach ($subchildren as $subchild) {
                if ($subchild === $parent) {
                    $is_child = true;
                    break;
                }
            }
        }
        if (!$is_child) {
//            echo $parent . " is not a child" . PHP_EOL;
            if (!in_array($parent, $not_children, true)) {
                $not_children[] = $parent;
            }
        }
    }
// First letter (in the alphabet) goes first
    sort($not_children);
    foreach ($not_children as $elem) {
        if (!isset($todo[$elem])) {
            $todo[$elem] = true;
        }
    }
    foreach ($todo as $not_a_child => $_tmp) {
        if (!isset($in_progress[$not_a_child])) {
            foreach ($workers as $id => [$status, $seconds]) {
                if ($status === false) {
                    echo "Assigning job " . $not_a_child . " to worker " . $id . " for " . ($letters[$not_a_child] + $letter_time) . " seconds" . PHP_EOL;
                    // We have a free worker!!! yeah! let's assign a job!
                    $workers[$id] = [true, $letters[$not_a_child] + $letter_time, $not_a_child,];
                    $todo = array_diff($todo, [$not_a_child]);
                    $in_progress[$not_a_child] = true;
                    break;
                }
            }
        }
    }
}

echo "The second part goes in this order: " . $step_order . "\n";

// stop the second run timer
echo "\nSecond part run time (seconds): " . (microtime(true) - $time_start2) . "\n";