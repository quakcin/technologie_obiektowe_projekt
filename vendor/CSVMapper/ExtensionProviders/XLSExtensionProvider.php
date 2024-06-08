<?php

  namespace CSVMapper\ExtensionProvider;

use CSVMapper\ExtensionProvider\ExtensionProvider;

class XLSExtensionProvider implements ExtensionProvider
{

  public function write ($file, $csv)
  {
    echo "WRITING TO: " . $file . "\n";
    file_put_contents($file, $csv);
  }

  public function read ($file)
  {
    echo "READING FROM: " . $file . "\n";
    return file_get_contents($file);
  }
}