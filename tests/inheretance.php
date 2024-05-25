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

namespace TestDziedziczenia;
use CSVMapper\Boostrapper\CSVMapperInjector;
use Tests;

class Osoba
{
  protected $imie;
  protected $nazwisko;

  public function getImie ()
  {
    return $this->imie;
  }

  public function getNazwisko ()
  {
    return $this->nazwisko;
  }

  public function setImie ($imie)
  {
    $this->imie = $imie;
  }

  public function setNazwisko ($nazwisko)
  {
    $this->nazwisko = $nazwisko;
  }
}

interface Witalny
{
  public function hello();
}

class Student extends Osoba implements Witalny
{
  private $numerIndeksu;

  public function getNumerIndeks()
  {
    return $this->numerIndeksu;
  }

  public function setNumerIndeks ($nr)
  {
    $this->numerIndeksu = $nr;
  }

  public function hello ()
  {
    return "Siema";
  }
}

class TestDziedziczenia extends Tests\Test
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
    parent::__construct("Dziedziczenie");
  }

  public function test ()
  {
    /** Test injectora */
    if ($this->mapper == null) {
      return $this->fail();
    }
  
    /** Przykładowy obiekt */
    $student = new Student();
    $student->setImie("Kamil");
    $student->setNazwisko("Ślimak");
    $student->setNumerIndeks("abc123");
    
    /** Test serializacji */
    $this->mapper->save($student);
    $fromFile = $this->mapper->read("./TestDziedziczenia\\Student.csv", Student::class);

    if ($fromFile->getImie() != $student->getImie()) {
      return $this->fail();
    }

    if ($fromFile->getNazwisko() != $student->getNazwisko()) {
      return $this->fail();
    }
    
    if ($fromFile->getNumerIndeks() != $student->getNumerIndeks()) {
      return $this->fail();
    }

    if (!($fromFile instanceof Student)) {
      return $this->fail();
    }
      
    if (!($fromFile instanceof Osoba)) {
      return $this->fail();
    }
      
    if (!($fromFile instanceof Witalny)) {
      return $this->fail();
    }

    return $this->pass();
  }
}