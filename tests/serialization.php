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

namespace TestSerializacjiObiektow;
use CSVMapper\Boostrapper\CSVMapperInjector;
use Tests;


class Student
{
  private $imie;
  private $wiek;

  public function toString ()
  {
    return "Student o imieniu: " . $this->imie . " ma " . $this->wiek . " lat!";
  }

  public function getImie() 
  {
      return $this->imie;
  }

  public function setImie($imie) 
  {
      $this->imie = $imie;
  }

  public function getWiek() 
  {
      return $this->wiek;
  }

  public function setWiek($wiek) 
  {
      $this->wiek = $wiek;
  }
}

class TestSerializacjiObiektow extends Tests\Test
{
  use CSVMapperInjector;

  /**
   * TODO: Naprawić mappera (pierwszy path nie zchodzi z configa)
   * @CSVMapper
   * @CSVMapperPath(./test.csv)
   */
  private $mapper;

  public function __construct ()
  {
    $this->injectDependencies();
    parent::__construct("Serializacja Obiektów (Zapis/Odczyt)");
  }

  public function test ()
  {
    /** Test injectora */
    if ($this->mapper == null) {
      return $this->fail();
    }
  
    /** Przykładowy obiekt */
    $student = new Student();
    $student->setWiek(18);
    $student->setImie("Tomek");
    
    /** Test serializacji */
    $this->mapper->save($student);
    $fromFile = $this->mapper->read("./TestSerializacjiObiektow\Student.csv", Student::class);

    if (get_class($fromFile) != Student::class) {
      return $this->fail();
    }

    if ($student->toString() != $fromFile->toString()) {
      return $this->fail();
    }

    return $this->pass();
  }
}