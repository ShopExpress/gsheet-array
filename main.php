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
    '1qi8bzOrIHBrIKCsGtKz6Q6pZLxHcQy7e9RZzjPsExBk'
);
foreach ($gsheetArray as $row) {
    echo implode(', ', $row) . PHP_EOL;
}

