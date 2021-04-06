<?php

$lstrng = "ooo+x+x+x";

$arr = [
    [0, 1, 2], [3, 4, 5],
    [6, 7, 8], [0, 3, 6],
    [1, 4, 7], [2, 5, 8],
    [0, 4, 8], [2, 4, 6]
];

foreach ($arr as $match) {
    if (($lstrng[$match[0]] === $lstrng[$match[1]])
        && ($lstrng[$match[1]] === $lstrng[$match[2]] && $lstrng[$match[2]] !== " ")
    ) {
        print("FOUND");
        print_r($match);
    }
}
