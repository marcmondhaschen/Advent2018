<?php
const COLOR_NICE_PINK = 197;
const COLOR_NICE_RED = 196;
const COLOR_NICE_BLUE = 69;
const COLOR_NICE_GREEN = 46;

gc_disable();
// examples
//list($nbOfPlayers, $lastMarbleWOrth, $expectedHS) = [10, 1618, 8317];
//list($nbOfPlayers, $lastMarbleWOrth, $expectedHS) = [13, 7999, 146373];
//list($nbOfPlayers, $lastMarbleWOrth, $expectedHS) = [17, 1104, 2764];
//list($nbOfPlayers, $lastMarbleWOrth, $expectedHS) = [21, 6111, 54718];
//list($nbOfPlayers, $lastMarbleWOrth, $expectedHS) = [30, 5807, 37305];
//
// question for part 1
list($nbOfPlayers, $lastMarbleWOrth, $expectedHS) = [431, 70950, null];
// question for part2
//list($nbOfPlayers, $lastMarbleWOrth, $expectedHS) = [431, 7095000, null];
// aaaand… this is a segfault
$circle = new CircleOfMarbles();
list($turn, $marble) = $circle->addFirstTwoMarbles();
$playersScores = array_fill(0, $nbOfPlayers, 0);
while($turn < $lastMarbleWOrth) {
    list($turn, $marble, $score) = $circle->makeTurn($turn, $marble);
    $playersScores[$turn%$nbOfPlayers] += $score;
}
say(bashColor(COLOR_NICE_BLUE, '[PART 1] ').max($playersScores));
if(!is_null($expectedHS)) {
    say('expected was '.$expectedHS);
}
class CircleOfMarbles
{
    public function addFirstTwoMarbles()
    {
        $firstMarble = new Marble();
        $secondMarble = new Marble();
        $firstMarble->after = $secondMarble;
        $secondMarble->after = $firstMarble;
        $firstMarble->before = $secondMarble;
        $secondMarble->before = $firstMarble;
        return [1, $secondMarble];
    }
    public function makeTurn($turn, $marble)
    {
        $turn++;
        $newMarble = new Marble();
        if ($newMarble->value%23 > 0) {
            $marble->after->insertAfter($newMarble);
            //$newMarble->debug($newMarble->value);
            return [$turn, $newMarble, 0];
        } else {
            $score = $newMarble->value;
            Marble::$activeMarbleCount--; // no insertion of new marble
            $sevenCounterClockwise = $marble->before->before->before->before->before->before->before;
            $score += $sevenCounterClockwise->value;
            $marble = $sevenCounterClockwise->after;
            $sevenCounterClockwise->remove();
            unset($sevenCounterClockwise);
            //$marble->debug($marble->value);
            return [$turn, $marble, $score];
        }
        return ;
    }
}
class Marble
{
    public $value;
    public $before;
    public $after;
    public static $marbleCount = 0;
    public static $activeMarbleCount = 0;
    public function __construct()
    {
        $this->value = self::$marbleCount++;
        self::$activeMarbleCount++;
    }
    public function insertAfter(Marble $m)
    {
        // before:
        // $this ---> $after
        // after:
        // $this ---> $m ----> $after
        $m->after = $this->after;
        $this->after->before = $m;
        $m->before = $this;
        $this->after = $m;
    }
    public function remove()
    {
        $this->before->after = $this->after;
        $this->after->before = $this->before;
        self::$activeMarbleCount--;
    }
    public function debug($value = null)
    {
        $marble = $this;
        for ($i = 0; $i < self::$activeMarbleCount; $i++) {
            say($marble->value . ' ', ($marble->value === $value ? COLOR_NICE_PINK : null), false);
            $marble = $marble->after;
        }
        say(''); // to have a EOL
    }
}


function say($something, $color = null, $withEol = true) {
    if (is_array($something)) {
        $something = (implode(' ', $something));
    }
    if (!is_null($color) && is_int($color) && $color <= 256) {
        $something = bashColor($color, $something);
    }
    echo $something . ($withEol ? PHP_EOL : '');
}
function bashColor($color, $string) {
    return "\e[38;5;${color}m".$string."\e[39m";
}
function checkEquals($expected, $actual, $message = '')
{
    if (!$message) {
        $message = "$expected == $actual";
    }
    if ($expected == $actual) {
        say(bashColor(COLOR_NICE_GREEN, "[OK] ").$message);
    } else {
        say(bashColor(COLOR_NICE_RED, "[KO] ").$message);
    }
}
function getMaxValueAndKey($array) {
    $maxValue = max($array);
    $maxKey = array_search($maxValue, $array);
    return [$maxKey, $maxValue];
}
function parseInt($str) {
    return (int)$str;
}
function integers(int $number, $start = 0) {
    for ($i = 0 ; $i < $number ; $i++) {
        yield $i => $i + $start;
    }
}
function extractMinAndMax($values) {
    $min = (int)$values[0];
    $max = (int)$values[0];
    foreach ($values as $value) {
        $value = (int)$value;
        if ($value > $max) {
            $max = $value;
        } elseif ($value < $min) {
            $min = $value;
        }
    }
    return [$min, $max];
}
function sortString($string) {
    $chars = str_split($string);
    sort($chars);
    return implode('', $chars);
}
function generateAlphabet()
{
    foreach (str_split('abcdefghijklmnopqrstuvwxyz') as $letter) {
        yield $letter;
    }
}