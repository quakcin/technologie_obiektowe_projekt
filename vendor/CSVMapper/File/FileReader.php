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

/**
 * 
 */
class FileReader
{
  private $instances = [];

  private $fileManager;
  private $path;

  private $content;

  private $header;

  private $classdef;

  private $objects;

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
    return $this->parseObjects();
  }

  private function readFile ()
  {
    $this->content = [];
    $str = file_get_contents($this->path);
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
      echo "reading path: $path\n";
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

      /* reflektor - do zarządzania klasami, $obj - instancja do zwrócenia */
      list($reflector, $obj) = $this->createClassInstance($this->classdef);
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
        $prop->setValue($obj, $values);
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

    $prop->setValue($obj, $line[$pos]);
  }

  private function findObjectByIdAndClassdef ($id, $classdef)
  {
    return $this->fileManager->findObjectByIdAndClassdef($id, $classdef);
  }

  public function findObjectById ($id)
  {
    return $this->objects[$id];
  }

  private function createClassInstance ($classdef)
  {
    $reflector = new ReflectionClass($classdef);
    $obj = $reflector->newInstance();
    return [$reflector, $obj];
  }
}