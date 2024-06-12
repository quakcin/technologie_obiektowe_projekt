<?php

namespace CSVMapper\ExtensionProvider;

use CSVMapper\ExtensionProvider\ExtensionProvider;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class XLSExtensionProvider implements ExtensionProvider
{

  private $fileName;
  private $spreedSheet;
  private $ids = [];

  public function __construct ($fileName = 'xlsmapper.xlsx')
  {
    $this->fileName = $fileName;
    $this->initFile();
  }

  private function initFile ()
  {
    if (file_exists($this->fileName)) {
      return;
    }

    $this->spreadSheet = new Spreadsheet();
    $sheet = $this->spreadSheet->getActiveSheet();
    $sheet->setTitle('Index');
    $writer = new Xlsx($this->spreadSheet);
    $writer->save($this->fileName);
  }

  public function write ($file, $csv)
  {
    $this->spreadSheet = IOFactory::load($this->fileName);
    $nameCode = $this->indexFile($file);

    if (!$this->spreadSheet->sheetNameExists($nameCode)) {
      $newSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($this->spreadSheet, $nameCode);
      $this->spreadSheet->addSheet($newSheet);
      /** Dodajemy do indeksu dla ludzi */
      $this->spreadSheet->setActiveSheetIndexByName("Index");
      $index = $this->spreadSheet->getActiveSheet();
      $row = $index->getHighestRow() + 1;
      $index->setCellValue([1, $row], $nameCode);
      $index->setCellValue([2, $row], $file);
    }

    $this->spreadSheet->setActiveSheetIndexByName($nameCode);
    $sheet = $this->spreadSheet->getActiveSheet();

    /**
     * Zapisujemy dane
     */
    $lines = explode("\n", $csv);
    $header = explode(";", array_shift($lines));

    for ($i = 0; $i < count($header); $i++) {
      $sheet->setCellValue([$i + 1, 1], $header[$i]);
    }
    
    for ($y = 0; $y < count($lines); $y++) {
      $toks = explode(";", $lines[$y]);
      for ($x = 0; $x < count($toks); $x++) {
        $sheet->setCellValue([$x + 1, $y + 2], $toks[$x]);
      }
    }

    $writer = new Xlsx($this->spreadSheet);
    $writer->save($this->fileName);
  }

  public function read ($file)
  {
    $nameCode = $this->indexFile($file);
    $this->spreadSheet->setActiveSheetIndexByName($nameCode);
    $sheet = $this->spreadSheet->getActiveSheet();

    $header = [];
    $body = [];
    $isHeader = true;

    foreach ($sheet->getRowIterator() as $row) {
      $values = [];
      foreach ($row->getCellIterator() as $cell) {
        if ($isHeader) {
          $header[] = $cell->getValue();
        } else {
          $values[] = $cell->getValue();
        }
      }

      if (!$isHeader) {
        $body[] = implode(";", $values);
      }

      $isHeader = false;
    }
  
    return implode(";", $header) . "\n" . implode("\n", $body);
  }

  public function indexFile ($name)
  {
    if (!in_array($name, $this->ids)) {
      $ids[] = $name;
    }

    return (string) array_search($name, $this->ids);
  }

}