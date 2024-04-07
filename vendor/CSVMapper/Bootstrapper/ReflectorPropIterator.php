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

namespace CSVMapper\Boostrapper;
use CSVMapper\Iterator\Iterator;

class ReflectorPropIterator extends Iterator
{
  private $prop;
  private $docs;

  public function hasAnnotation ()
  {
    return preg_match('/@CSVMapper/', $this->docs) > 0;
  }

  /**
   * Ekstraktuje ścieżkę do mapowanego pliku
   */
  public function getPath ()
  {
    if (preg_match("/@CSVMapperPath\((.*?)\)/", $this->docs, $matches)) {
      return $matches[0];
    }

    return false;
  }

  public function hasPath ()
  {
    return preg_match("/@CSVMapperPath\((.*?)\)/", $this->docs) > 0;
  }

  public function getDocs()
  {
    return $this->docs;
  }

  public function setDocs($docs)
  {
    $this->docs = $docs;
  }

  public function setProp ($prop)
  {
    $this->prop = $prop;
  }

  public function getProp ()
  {
    return $this->prop;
  }
}