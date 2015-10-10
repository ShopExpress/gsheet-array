<?php
/**
 * This is not production code, this is just an example
 * how to use the GSheetArray.
 */

require_once __DIR__ . '/vendor/autoload.php';

/*
 * Prepare GSheetArray instance
 */
$gsheetArray = new \Ychuperka\GS2a\GSheetArray(
    '1AEf_H2hfk8l5ZsCZ-SnaQGp-pfC3suLE9SbO3rfJ1ow'
);
foreach ($gsheetArray as $row) {
    echo implode(', ', $row) . PHP_EOL;
}

