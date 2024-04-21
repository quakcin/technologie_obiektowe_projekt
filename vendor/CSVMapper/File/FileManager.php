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

use CSVMapper\File\FileReader;

/**
 * 
 */
class FileManager
{

  private $objects = [];
  private $readers = [];
  private $sourceObject = null;

  /**
   * Konstruktor przyjmuje ścieżkę do pliku źródłowego
   * którego podaje do pierwszego File
   */
  public function __construct ($source, $classdef)
  {
    $this->sourceObject = $this->openReader($this, $source, $classdef)[0];
  }

  public function openReader ($fileManager, $path, $classdef)
  {
    if (isset($readers[$classdef])) {
      echo "reader allready exists";
      return;
    }

    $reader = new FileReader($this, $path, $classdef);
    $this->readers[$classdef] = $reader;
    return $reader->read();
  }

  public function getSourceObject ()
  {
    return $this->sourceObject;
  }


  public function findObjectByIdAndClassdef ($id, $classdef)
  {
    return $this->readers[$classdef]->findObjectById($id);
  }
}