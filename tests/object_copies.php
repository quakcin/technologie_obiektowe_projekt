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

namespace TestObjectCopies;
use CSVMapper\Boostrapper\CSVMapperInjector;
use Tests;

/**
 * Obiekt A zawiera 3 pola:
 * b1 = b2
 * oraz b3
 */
class A
{
  private $b1;
  private $b2;
  private $b3;

  public function setB ($b1, $b2, $b3)
  {
    $this->b1 = $b1;
    $this->b2 = $b2;
    $this->b3 = $b3;
  }

  public function getB ()
  {
    return [$this->b1, $this->b2, $this->b3];
  }

}

class B
{
  public $value = "anything";
}


class TestObjectCopies extends Tests\Test
{
  use CSVMapperInjector;

  /**
   * @CSVMapper
   * @CSVMapperPath(./test.csv)
   */
  private $mapper;

  public function __construct ()
  {
    $this->injectDependencies();
    parent::__construct("Kopie obiektów");
  }

  public function test ()
  {
    /** Test injectora */
    if ($this->mapper == null) {
      return $this->fail();
    }

    /** Zapisywany obiekt A zawiera dwie referencje (b1, b2) na obiekt B1 */
    $b1 = new B();

    /** Oraz jedną referencję (b3) na obiekt B3 */
    $b3 = new B();

    $a = new A();
    $a->setB($b1, $b1, $b3);

    $this->mapper->save($a);
    $fromFile = $this->mapper->read("./TestObjectCopies\A.csv", A::class);

    /** Sprawdzamy kopie referencji */
    list($rb1, $rb2, $rb3) = $fromFile->getB();

    if ($rb1 !== $rb2) {
      return $this->fail();
    }

    if ($rb1 === $rb3 || $rb2 === $rb3) {
      return $this->fail();
    }

    return $this->pass();
  }
}