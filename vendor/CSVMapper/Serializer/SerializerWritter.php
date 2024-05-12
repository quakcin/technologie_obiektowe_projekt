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


namespace CSVMapper\Serializer;

/**
 * Zapisywanie wewnętrznej reprezentacji obiektowej do
 * plików CSV. Na chwilę obecną nazwa pliku to nazwa
 * klasy + sufix .csv
 */
class SerializerWriter
{

  private $pool;

  public function __construct($pool)
  {
    $this->pool = $pool;
  }

  public function write ()
  {
    foreach ($this->pool as $className => $objects) {
      $this->writeClass($className, $objects);
    }
  }

  private function writeClass ($className, $objects)
  {
    /**
     * Format:
     * 0:  ID; NAZWA_POLA; NAZWA_POLA; ...
     * 1:   x;    WARTOŚĆ; ...
     */

    $fields = [];

    foreach ($objects[0] as $specs) {
      $fields[] = $specs->key;
    }

    $outs = [];

    $outs[] = implode("; ", $fields);
    foreach ($objects as $obj) {
      $ent = [];
      foreach ($obj as $prop) {
        $ent[] = $prop->value;
      }

      $outs[] = implode("; ", $ent);
    }

    $outs = implode("\n", $outs);

    /**
     * FIX: Na systemach windows nie działają nazwy z '\'
     *      mapper konwertuje je na '-'
     */
    file_put_contents("./" . str_replace("\\", "-", $className) . ".csv", $outs);
  }

}