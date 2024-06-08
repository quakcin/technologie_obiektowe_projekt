<?php

namespace CSVMapper\ExtensionProvider;

use CSVMapper\ExtensionProvider\ExtensionProvider;
use DOMDocument;

/**
 * Zapis w formacie XML:
 * <Mapping>
 *  <Definition>
 *    <Type>~list@ofClasses</Type>
 *    <Type>value</Type>
 *    ...
 *    <Type>id#</Type>
 *  </Definition>
 *  <Content>
 *    <Entity>
 *      <Value>#1, #2, #3</Value>
 *      <Value>1234</Value>
 *      ...
 *      <Value>#1</Value>
 *    </Entity>
 *  </Content>
 * </Mapping>
 */
class XMLExtensionProvider implements ExtensionProvider
{

  /**
   * Zanim zapiszemy, musimy przekonwertowac nasz format CSV
   * na nowy format XML
   */
  public function write ($file, $csv)
  {
    $lines = explode("\n", $csv);
    $header = explode(";", array_shift($lines));

    $domDoc = new DOMDocument;
    $root = $domDoc->createElement('Mapping');
    $domDoc->appendChild($root);
    $definiton = $domDoc->createElement('Definition');
    $root->appendChild($definiton);

    foreach ($header as $tok) {
      $value = $domDoc->createElement('Field');
      $text = $domDoc->createTextNode($tok);
      $value->appendChild($text);
      $definiton->appendChild($value);
    }

    $content = $domDoc->createElement('Content');
    $root->appendChild($content);

    foreach ($lines as $line) {
      $toks = explode(";", $line);
      $entity = $domDoc->createElement('Entity');
      $content->appendChild($entity);
      foreach ($toks as $tok) {
        $value = $domDoc->createElement('Value');
        $entity->appendChild($value);

        $text = $domDoc->createTextNode($tok);
        $value->appendChild($text);
      }
    }

    file_put_contents($file . ".xml", $domDoc->saveXML());
  }

  public function read ($file)
  {
    $xml = file_get_contents($file . ".xml");
    $domDoc = new DOMDocument();
    $domDoc->loadXML($xml);

    $csvHeader = [];
    $csvBody = [];

    $definition = $domDoc->getElementsByTagName('Definition')[0];
    $fields = $definition->getElementsByTagName('Field');

    foreach ($fields as $field) {
      $csvHeader[] = $field->textContent;
    }

    $content = $domDoc->getElementsByTagName('Content')[0];
    $entities = $content->getElementsByTagName('Entity');

    foreach ($entities as $entity) {
      $values = $entity->getElementsByTagName('Value');
      $csvValues = [];
      foreach ($values as $value) {
        $csvValues[] = $value->textContent;
      }
      $csvBody[] = implode(";", $csvValues);
    }

    return implode(";", $csvHeader) . "\n" . implode("\n", $csvBody);
  }
}