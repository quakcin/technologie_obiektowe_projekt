<?php
/**
 * MIT License
 * 
 * Copyright (c) 2024 Marcin Ślusarczyk, Maciej Bandura 
 *               Kielce University of Technology
 *               Politechnika Świętokrzyska WEAII
 * 
 *     https://opensource.org/license/mit
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace CSVMapper\File;

use CSVMapper\Header\CSVHeader;
use ReflectionClass;

class FileReader
{
  private $instances = [];

  private $fileManager;
  private $path;

  private $content;

  private $header;

  private $classdef;

  private $objects = [];

  private $parsedObjects;

  public function __construct ($fileManager, $path, $classdef)
  {
    $this->classdef=  $classdef;
    $this->fileManager = $fileManager;
    $this->path = $path;
  }

  /**
   * Funkcja wczytuje obiekty z pliku, binduje je przy
   * pomocy id# w $this->instances, oraz rekurencyjnie
   * wywołuje FileManager::openReader() jeżeli obiekty
   * korzystają z mapowań do innych obiektów
   */
  public function read ()
  {
    $this->readFile();
    $this->parseCSVHeader();
    $this->recurseExternalFiles();

    $this->parsedObjects = $this->parseObjects();
    return $this->parsedObjects;
  }

  public function getParsedObjects ()
  {
    return $this->parsedObjects;
  }

  private function readFile ()
  {
    $this->content = [];
    /**
     * FIX: Nazwy na windowsie
     */
    $str = file_get_contents(str_replace("\\", "-", $this->path));
    $lines = explode("\n", $str);
    foreach ($lines as $line) {
      $this->content[] = explode("; ", $line);
    }
  }

  private function parseCSVHeader ()
  {
    $this->header = new CSVHeader($this->content[0]);
  }

  private function recurseExternalFiles ()
  {
    $paths = $this->header->getPaths();
    $paths = array_keys((array) $paths);

    foreach ($paths as $classdef) {
      $path = "./$classdef.csv";
      $this->fileManager->openReader(
        $this->fileManager,
        $path,
        $classdef
      );
    }
  }

  /**
   * Metoda zwraca listę odtworzonych obiektów, CSVMapper::read()
   * zwraca pierwszy obiekt z tej list (w zasadzie jedyny).
   * @return array - lista odtworzonych obiektów
   */
  private function parseObjects ()
  {
    $objects = [];

    /* Dla każdego obiektu w pliku: */
    for ($ln = 1; $ln < count($this->content); $ln++) {
      $line = $this->content[$ln];
      $key = $line[count($line) - 1];

      /* reflektor - do zarządzania klasami, $obj - instancja do zwrócenia */
      
      list($reflector, $obj) = $this->createClassInstance($this->classdef, $key);
      $objects[] = $obj;
      $this->objects[$line[$this->header->getId()]] = $obj;

      /* Dla każdej kolumny (pola) */
      for ($pos = 0; $pos < count($line); $pos++) {
        $this->createFieldFromColumn($reflector, $obj, $pos, $line);
      }

      // var_dump($line);
    }

    return $objects;
  }

  /**
   * Na ten moment, typy mapowane są dynamicznie na bazie ich
   * zawartości, tak jak to robi PHP
   */
  private function fixType ($str)
  {
    /* Każda nie numeryczna wartość jest ciągiem znaków */
    if (!is_numeric($str)) {
      return $str;
    }

    /* Z kropką zawsze float */
    if (strpos($str, ".") !== false) {
      return (float) $str;
    }

    return (int) $str;
}

  private function createFieldFromColumn ($reflector, $obj, $pos, $line)
  {
    $header = $this->header->getNames();

    if ($pos == $this->header->getId()) {
      return;
    }

    $prop = $reflector->getProperty($header[$pos]);

    if (in_array($pos, $this->header->getLists())) {
      $prop->setValue($obj, []);

      /**
       * Istnieją dwie opcje:
       *  1. lista prymitywnych wartości
       *  2. lista obiektów 
       */
      $values = explode(",", $line[$pos]);
      if (!in_array($pos, $this->header->getPaths())) {
        /** Prymityw */
        /**
         * FIX: Mapowanie typów
         */
        $typedValues = [];
        foreach ($values as $val) {
          $typedValues[] = $this->fixType($val);
        }

        $prop->setValue($obj, $typedValues);
        return;
      }

      /** Obiekt */
      $externalClassDef = $this->header->getClassdefFromPosition ($pos);
      
      $arr = [];
      foreach ($values as $externalId) {
        $arr[] = $this->findObjectByIdAndClassdef($externalId, $externalClassDef);
      }
      $prop->setValue($obj, $arr);
      return;
    }

    if (in_array($pos, $this->header->getPaths())) {
      /** Obiekt nie w liście */
      $externalClassDef = $this->header->getClassdefFromPosition($pos);
      $externalObj = $this->findObjectByIdAndClassdef($line[$pos], $externalClassDef);
      $prop->setValue($obj, $externalObj);
      return;
    }

    /**
     * W każdym przeciwnym wypadku, mamy prostą prymitywną wartość
     */

    $prop->setValue($obj, $this->fixType($line[$pos]));
  }

  private function findObjectByIdAndClassdef ($id, $classdef)
  {
    return $this->fileManager->findObjectByIdAndClassdef($id, $classdef);
  }

  public function findObjectById ($id)
  {
    if (in_array($id, $this->objects) == false) {
      list($ref, $obj) = $this->createClassInstance($this->classdef, $id);
      return $obj;
    }

    return $this->objects[$id];
  }



  private function createClassInstance ($classdef, $key)
  {
    /**
     * WARN: Zamiast nowej instancji ReflectionClass potrzebujemy
     *       istniejacej instancji z jakiegos indeksu, o ile istnieje
     */
    if (isset($this->fileManager->getInstanceIndex[$key])) {
      return $this->fileManager->getInstanceIndex[$key];
    }
    $reflector = new ReflectionClass($classdef);
    $obj = $reflector->newInstance();
    $this->fileManager->getInstanceIndex[$key] = [$reflector, $obj];

    return [$reflector, $obj];
  }
}