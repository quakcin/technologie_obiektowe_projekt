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

namespace TestKompozycja;
use CSVMapper\Boostrapper\CSVMapperInjector;
use Tests;


class Silnik
{
  private $moc;

  public function getMoc()
  {
    return $this->moc;
  }

  public function setMoc($moc)
  {
    $this->moc = $moc;
  }
}

class Samochod
{
    private $model;
    private Silnik $silnik;

    public function getModel()
    {
        return $this->model;
    }

    public function setModel($model)
    {
        $this->model = $model;
    }

    public function getSilnik(): Silnik
    {
        return $this->silnik;
    }

    public function setSilnik(Silnik $silnik)
    {
        $this->silnik = $silnik;
    }
}


class TestKompozycja extends Tests\Test
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
    parent::__construct("Kompozycja");
  }

  public function test ()
  {
    /** Test injectora */
    if ($this->mapper == null) {
      return $this->fail();
    }
  
    $silnik = new Silnik();
    $silnik->setMoc("245KM");

    $bmw = new Samochod();
    $bmw->setModel("528i");
    $bmw->setSilnik($silnik);
    
    /** Test */
    $this->mapper->save($bmw);
    $fromFile = $this->mapper->read("./TestKompozycja\Samochod.csv", Samochod::class);

    if ($fromFile->getModel() != $bmw->getModel()) {
      return $this->fail();
    }

    if (!($fromFile->getSilnik() instanceof Silnik)) {
      return $this->fail();
    }

    if ($fromFile->getSilnik()->getMoc() != $silnik->getMoc()) {
      return $this->fail();
    }

    return $this->pass();
  }
}