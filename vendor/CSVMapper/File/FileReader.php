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

  public function __construct ($fileManager, $path)
  {
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
   * W teori w tym miejscu już powinniśmy mieć wyższe odtworzone
   * obiekty w FileManager, wiec powinnismy odtwarzac juz gotowy
   * obiekt i go zwracac?
   * 
   * @return mixed|object|array
   */
  private function parseObjects ()
  {
    // TODO: zwrócić odtworzony obiekt, lub listę obiektów
  }
}