<?php

namespace CSVMapper\ExtensionProvider;

use CSVMapper\ExtensionProvider\ExtensionProvider;

class CSVExtensionProvider implements ExtensionProvider
{

  public function write ($file, $csv)
  {
    file_put_contents($file, $csv);
  }

  public function read ($file)
  {
    return file_get_contents($file);
  }
}