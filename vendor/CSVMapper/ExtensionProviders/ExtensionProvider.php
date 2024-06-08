<?php

namespace CSVMapper\ExtensionProvider;

interface ExtensionProvider
{
  public function write ($file, $csv);
  public function read ($file);
}