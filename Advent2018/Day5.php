<?php
/**
 * Author: /u/sqlr
 * Date: 12/5/2018
 * Time: 7:09 PM
 */

/*
 *
 * --- Day 5: Alchemical Reduction Part 1---
 *
 * You've managed to sneak in to the prototype suit manufacturing lab. The Elves
 * are making decent progress, but are still struggling with the suit's
 * size reduction capabilities.
 *
 * While the very latest in 1518 alchemical technology might have solved their
 * problem eventually, you can do better. You scan the chemical composition of the
 * suit's material and discover that it is formed by extremely long polymers (one
 * of which is available as your puzzle input).
 *
 * The polymer is formed by smaller units which, when triggered, react with each
 * other such that two adjacent units of the same type and opposite polarity are
 * destroyed. Units' types are represented by letters; units' polarity is
 * represented by capitalization. For instance, r and R are units with the same
 * type but opposite polarity, whereas r and s are entirely different types and
 * do not react.
 *
 * For example:
 * In aA, a and A react, leaving nothing behind.
 * In abBA, bB destroys itself, leaving aA. As above, this then destroys itself,
 * leaving nothing.
 * In abAB, no two adjacent units are of the same type, and so nothing happens.
 * In aabAAB, even though aa and AA are of the same type, their polarities match,
 * and so nothing happens.
 * Now, consider a larger example, dabAcCaCBAcCcaDA:
 *
 * dabAcCaCBAcCcaDA  The first 'cC' is removed.
 * dabAaCBAcCcaDA    This creates 'Aa', which is removed.
 * dabCBAcCcaDA      Either 'cC' or 'Cc' are removed (the result is the same).
 * dabCBAcaDA        No further actions can be taken.
 *
 * After all possible reactions, the resulting polymer contains 10 units.
 *
 * How many units remain after fully reacting the polymer you scanned? (Note: in
 * this puzzle and others, the input is large; if you copy/paste your input, make
 * sure you get the whole thing.)
*/

// consider recursive reduction with the exit condition being equivalent length input/output

const REACTING_UNITS = [
    'aA', 'Aa', 'bB', 'Bb', 'cC', 'Cc', 'dD', 'Dd', 'eE', 'Ee', 'fF', 'Ff', 'gG', 'Gg',
    'hH', 'Hh', 'iI', 'Ii', 'jJ', 'Jj', 'kK', 'Kk', 'lL', 'Ll', 'mM', 'Mm', 'nN', 'Nn',
    'oO', 'Oo', 'pP', 'Pp', 'qQ', 'Qq', 'rR', 'Rr', 'sS', 'Ss', 'tT', 'Tt', 'uU', 'Uu',
    'vV', 'Vv', 'wW', 'Ww', 'xX', 'Xx', 'yY', 'Yy', 'zZ', 'Zz',
];

$problem_data = file_get_contents('day5data',true);

function reduced_length($string) {
    do {
        $string = str_replace(REACTING_UNITS, '', $string, $count);
    } while ($count > 0);
    return strlen($string);
}

echo "Part 1 is " . reduced_length($problem_data) . " characters long.\n";

$problem_data2 = file_get_contents('day5data',true);
$shortest = PHP_INT_MAX;

foreach (range('a', 'z') as $troublemaker) {
    $polymer = str_replace([$troublemaker, strtoupper($troublemaker)], '', $problem_data2);
    $shortest = min($shortest, reduced_length($polymer));
}

echo "Part 2 the shortest polymer is " . $shortest . " characters long.\n";