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

use ReflectionClass;

class Serializer
{
  private $pool = [];

  public function dump ()
  {
    var_dump($this->pool);
  }

  private function serializeObject ($key, $value)
  {
    return (object) [
      "key" => $key . "@" . get_class($value),
      "value" => $this->serialize($value)
    ];
  }

  private function serializeArray ($key, $values)
  {
    $csvKey = "~" . $key;
    if (count($values) > 0 && is_object($values[0])) {
      $csvKey .= "@" . get_class($values[0]);
    }

    $keys = [];
    foreach ($values as $value) {
      $keys[] = $this->serialize($value);;
    }

    return (object) [
      "key" => $csvKey,
      "value" => implode(",", $keys)
    ];
  }


  private function findInPool ($obj)
  {
    $className = get_class($obj);
    if (!isset($this->pool[$className])) {
      return -1;
    }

    foreach ($this->pool[$className] as $pooled) {
      if ($pooled === $obj) {
        return $pooled->id;
      }
    }

    return -1;
  }

  public function write ()
  {
    (new SerializerWriter($this->pool))->write();
  }

  /**
   * Serializuje drzewo obiektów w wewnętrznym formacie
   */
  public function serialize ($obj)
  {
    /**
     * 0. Sprawdzamy czy obiekt nie jest już zaindexowany
     */
    $id = $this->findInPool ($obj);
    if ($id != -1) {
      return $id;
    }

    /**
     * 1. Jeżeli nie to go serializujemy ze wszystkimi podobiektami
     */
    $id = uniqid();
    $cols = [];

    $reflection = new ReflectionClass($obj);
    foreach ($reflection->getProperties() as $prop) {
      /** Pole dodawane przez PHP */
      if ($prop->getName() === 'class') {
        continue;
      }

      $key = $prop->getName();
      $value = $prop->getValue($obj);

      if (is_array($value)) {
        /** Tablice rozwijamy, szczególnie jeżeli obiekty */
        $cols[] = $this->serializeArray($key, $value);

      } else if (is_object($value)) {
        /** Rekurencyjnie serializujemy podobiekty */
        $cols[] = $this->serializeObject($key, $value);

      } else {
        /** Typy prymitywne po prostu zapisujemy dla encji */
        $cols[] = (object) [
          "key" => $key,
          "value" => $value
        ];
      }
    }

    /**
     * Zserializowanie id
     */
    $cols[] = (object) [
      "key" => "id#",
      "value" => $id
    ];

    /** TODO: Move object decl abolve, so no recursive loops stuff */
    $this->pool[get_class($obj)][] = $cols;
    return $id;
  }
}