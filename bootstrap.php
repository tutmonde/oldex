<?php declare(strict_types=1);
use oldex\SearchEngine;

function search(string $input): Array
{
    return new SearchEngine->searchWithDDG($input);
}

return (function() {});
