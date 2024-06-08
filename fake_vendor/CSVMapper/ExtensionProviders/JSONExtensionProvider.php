<?php

namespace CSVMapper\ExtensionProvider;

use CSVMapper\ExtensionProvider\ExtensionProvider;

/**
 * Konwerter do zapisu w formacie JSON
 */
class JSONExtensionProvider implements ExtensionProvider
{

  public function write ($file, $csv)
  {
    $lines = explode("\n", $csv);
    $header = explode(";", array_shift($lines));

    $root = (object) ["entities" => []];

    foreach ($lines as $line) {
      $toks = explode(";", $line);
      $newObj = [];
      for ($i = 0; $i < count($toks); $i++) {
        $newObj[$header[$i]] = $toks[$i];
      }
      $root->entities[] = (object) $newObj;
    }

    file_put_contents($file . ".json", json_encode($root));
  }

  public function read ($file)
  {
    $json = file_get_contents($file . ".json");
    $root = json_decode($json);

    $header = [];
    $entities = [];

    $first = (array) $root->entities[0];
    foreach ($first as $key => $value) {
      $header[] = $key;
    }

    foreach ($root->entities as $entity) {
      $values = [];
      foreach ((array) $entity as $value) {
        $values[] = $value;
      }
      $entities[] = implode(";", $values);
    }

    return implode(";", $header) . "\n" . implode("\n", $entities);
  }
}