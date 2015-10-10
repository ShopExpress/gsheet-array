# GSheetArray - a simple php class to access Google`s Sheets that implements ArrayAccess and Iterator

## Installation
composer require ychuperka/gsheet-array

## Set up document
Set up access by a link and cut document id from the linke,
i.e. from "https://docs.google.com/spreadsheets/d/{document_id}/edit?usp=sharing"
you should use a substring "{document_id}".

## Usage
```
$gsheetArray = new \Ychuperka\GS2a\GSheetArray(
    'document_file_id'
);
foreach ($gsheetArray as $row) {
    echo implode(', ', $row) . PHP_EOL;
}
```