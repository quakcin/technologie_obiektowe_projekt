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

namespace CSVMapper\Header;

/**
 * 
 */
class CSVHeader
{
  /**
   * @var $lists - Nazwy pól będąchcych listami/tablicami
   */
  private $lists = [];
  /**
   * @var $paths - Nazwy pól linkowanych plikami<->id
   */
  private $paths = [];
  /**
   * @var $id - pozycja id w pliku
   */
  private $id;

  /**
   * @param $header - tablica elementów w nagłówku CSV,
   *                  na jej podstawie zostanie odtworzony
   *                  obiekt nagłówka
   */
  public function __construct ($header)
  {
    $this->parseHeader($header);
  }

  private function parseHeader ($header)
  {
    $pos = 0;
    foreach ($header as $tok) {
      $this->parseToken($tok, $pos++);
    }

    var_dump($this);
  }

  private function parseToken ($tok, $pos)
  {
    if (strpos($tok, "#") !== false) {
      /** Pozycja id w nagłówku */
      $this->id = $pos;
      return;
    }

    if (strpos($tok, "@") !== false) {
      /** Linkowany obiekt */
      $subtoks = explode("@", $tok);
      $this->paths[$subtoks[1]] = $pos;
      $tok = $subtoks[0];
    }

    if ($tok[0] == "~") {
      /** Lista */
      $tok = substr($tok, 1);
      $this->lists[] = $tok;
    }
  }

  /**
   * Get; Set;
   */

  public function getLists ()
  {
    return $this->lists;
  }

  public function getPaths ()
  {
    return $this->paths;
  }

  public function getId ()
  {
    return $this->id;
  }
  
}