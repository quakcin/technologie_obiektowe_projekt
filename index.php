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

/**
 * W taki sposób typowa aplikacja ładowała by naszą bibliotekę
 */
require 'vendor/autoload.php';

use CSVMapper\Boostrapper\CSVMapperInjector;

class C
{
  private $x = 10;
  public function __construct ($x) {
    $this->x = $x;
  }
}

class B
{
  private $c;
  public function __construct ($x) {
    $this->c = new C($x);
  }
}

class A
{
  private $bFields;
  public function __construct () {
    $this->bFields = [];
    for ($i = 0; $i < 3; $i++) {
      $this->bFields[] = new B($i * 100);
    }
  }
}

class App 
{
  use CSVMapperInjector;

  private $a;

  public function __construct ()
  {
    $this->injectDependencies();
    $this->a = new A();
  }

  /**
   * @CSVMapper
   * @CSVMapperPath(./file1.csv)
   */
  private $csvMapper;

  public function main ()
  {
    $this->csvMapper->save($this->a);
  }

}

// $reflectionClass = new ReflectionClass(ExampleClass::class);
// $props = $reflectionClass->getProperties();
// foreach ($props as $prop) {
//   var_dump($prop->getDocComment());
// }

$app = new App();
$app->main();