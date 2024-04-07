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

use ReflectionClass;
use CSVMapper\CSVMapper;

class ReflectorInjector
{
  /**
   * Poszukiwacz pola z anotacją @CSVMapper
   */
  private $reflectionClass;
  private $context;

  public function __construct ($context)
  {
    $reflectionClass = new ReflectionClass($context::class);
    $this->reflectorPropSearcher = new ReflectorPropSearcher($reflectionClass);
    $this->context = $context;
  }

  /**
   * Metoda wszystrykująca CSVMappera do anotowanego pola
   */
  public function inject ()
  {
    $field = $this->reflectorPropSearcher->find();
    $field->setAccessible(true); /** TODO: remember old state! */
    $field->setValue($this->context, new CSVMapper());
  }
}