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

namespace CSVMapper;

use CSVMapper\Serializer\Serializer;
use CSVMapper\File\FileManager;
use CSVMapper\ExtensionProvider\CSVExtensionProvider;
use CSVMapper\ExtensionProvider\JSONExtensionProvider;
use CSVMapper\ExtensionProvider\XMLExtensionProvider;
use CSVMapper\ExtensionProvider\XLSExtensionProvider;

class CSVMapper
{

  private $extensionProvider;

  public function __construct ()
  {
    $this->extensionProvider = new CSVExtensionProvider();
    // $this->extensionProvider = new XMLExtensionProvider();
    // $this->extensionProvider = new JSONExtensionProvider();
    // $this->extensionProvider = new XLSExtensionProvider();
  }

  public function provideExtension ($provider)
  {
    $this->extensionProvider = $provider;
    return $this;
  }

  public function read ($path, $classdef)
  {
    $fileManger = new FileManager($path, $classdef, $this);
    return $fileManger->getSourceObject();
  }
  
  public function save ($obj)
  {
    $serializer = new Serializer($this);
    $serializer->serialize($obj);
    $serializer->write($obj);
  }

  public function getExtensionProvider ()
  {
    return $this->extensionProvider;
  }

}