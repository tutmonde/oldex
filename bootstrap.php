<?php declare(strict_types=1);
use oldex\SearchEngine;

function searchWithDDG(string $input): Array
{
    return (new SearchEngine)->searchWithDDG($input);
}

return (function() {
    if(is_dir($gitDir = OLDEX_ROOT . "/.git"))
        $ver = trim(`git --git-dir="$gitDir" log --pretty="%h" -n1 HEAD` ?? "Unknown version");
    else
        $ver = "Unknown version (git)";

    define('OLDEX_VERSION', 'Perlence '.$ver, false);
});
