# GSheetArray - a simple php class to access Google`s Sheets that implements ArrayAccess and Iterator

## Installation
composer require ychuperka/gsheet-array

## Usage
```
$gsheetArray = new \Ychuperka\GS2a\GSheetArray(
    'document_file_id'
);
foreach ($gsheetArray as $row) {
    echo implode(', ', $row) . PHP_EOL;
}
```