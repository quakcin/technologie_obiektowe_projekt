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

require './vendor/CSVMapper/Header/CSVHeader.php';
require './vendor/CSVMapper/ExtensionProviders/ExtensionProvider.php';
require './vendor/CSVMapper/ExtensionProviders/CSVExtensionProvider.php';
require './vendor/CSVMapper/ExtensionProviders/XLSExtensionProvider.php';

require './vendor/CSVMapper/File/FileManager.php';
require './vendor/CSVMapper/File/FileReader.php';

require './vendor/CSVMapper/Serializer/Serializer.php';
require './vendor/CSVMapper/Serializer/SerializerWritter.php';

require './vendor/CSVMapper/CSVMapper.php';
require './vendor/CSVMapper/Iterator/Iterator.php';

require './vendor/CSVMapper/ErrorHandling/Issue.php';
require './vendor/CSVMapper/ErrorHandling/Errors.php';
require './vendor/CSVMapper/ErrorHandling/Warnings.php';

require './vendor/CSVMapper/ErrorHandling/Warnings/NoFieldToInjectWarning.php';

require './vendor/CSVMapper/ErrorHandling/Errors/NoPathToMapError.php';


require './vendor/CSVMapper/Bootstrapper/ReflectorPropIterator.php';
require './vendor/CSVMapper/Bootstrapper/ReflectorPropExtractor.php';
require './vendor/CSVMapper/Bootstrapper/ReflectorPropSearcher.php';
require './vendor/CSVMapper/Bootstrapper/ReflectorInjector.php';
require './vendor/CSVMapper/Bootstrapper/CSVMapperInjector.php';
