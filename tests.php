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


namespace Tests;
require 'vendor/autoload.php';

const ANSI_RED = "\033[0;31m";
const ANSI_GREEN = "\033[0;32m";
const ANSI_RESET = "\033[0m";

include './tests/serialization.php';
include './tests/types.php';
include './tests/object_copies.php';
include './tests/cyclic_relations.php';

use TestTypes;
use TestSerializacjiObiektow;
use TestObjectCopies;
use TestCyclicRelations;

/**
 * Abstrakcyjna klasa bazowa dla testu
 */
abstract class Test
{

  private $testName = null;

  public function __construct ($testName)
  {
    $this->testName = $testName;
  }

  abstract public function test ();

  protected function pass ()
  {
    echo ANSI_GREEN . "[PASS] " . ANSI_RESET . $this->testName . "\n";
    return true;
  }

  protected function fail ()
  {
    echo ANSI_RED . "[FAILED] " . ANSI_RESET . $this->testName . "\n";
    return false;
  }
}

/**
 * Testy naszego mappera
 */
class Tests 
{
  private $tests = [];
  private $pass;
  private $fail;

  public function __construct ()
  {
    $this->tests[] = new TestSerializacjiObiektow\TestSerializacjiObiektow;
    $this->tests[] = new TestTypes\TestTypes;
    $this->tests[] = new TestObjectCopies\TestObjectCopies;
    $this->tests[] = new TestCyclicRelations\TestCyclicRelations;

    $this->runTests();
  }

  private function runTests ()
  {
    $this->pass = 0;
    $this->fail = 0;

    foreach ($this->tests as $test) {
      if ($test->test()) {
        $this->pass++;
      } else {
        $this->fail++;
      }
    }

    $this->testSummary();
  }

  private function testSummary ()
  {
    echo "Test Count: " . count($this->tests) . ", Passed: " . $this->pass . ", Failed: " . $this->fail . "\n";
  }

}

$tests = new Tests();