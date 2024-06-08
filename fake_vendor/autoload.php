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

require './fake_vendor/CSVMapper/Header/CSVHeader.php';
require './fake_vendor/CSVMapper/ExtensionProviders/ExtensionProvider.php';
require './fake_vendor/CSVMapper/ExtensionProviders/CSVExtensionProvider.php';
require './fake_vendor/CSVMapper/ExtensionProviders/XLSExtensionProvider.php';
require './fake_vendor/CSVMapper/ExtensionProviders/XMLExtensionProvider.php';
require './fake_vendor/CSVMapper/ExtensionProviders/JSONExtensionProvider.php';

require './fake_vendor/CSVMapper/File/FileManager.php';
require './fake_vendor/CSVMapper/File/FileReader.php';

require './fake_vendor/CSVMapper/Serializer/Serializer.php';
require './fake_vendor/CSVMapper/Serializer/SerializerWritter.php';

require './fake_vendor/CSVMapper/CSVMapper.php';
require './fake_vendor/CSVMapper/Iterator/Iterator.php';

require './fake_vendor/CSVMapper/ErrorHandling/Issue.php';
require './fake_vendor/CSVMapper/ErrorHandling/Errors.php';
require './fake_vendor/CSVMapper/ErrorHandling/Warnings.php';

require './fake_vendor/CSVMapper/ErrorHandling/Warnings/NoFieldToInjectWarning.php';

require './fake_vendor/CSVMapper/ErrorHandling/Errors/NoPathToMapError.php';


require './fake_vendor/CSVMapper/Bootstrapper/ReflectorPropIterator.php';
require './fake_vendor/CSVMapper/Bootstrapper/ReflectorPropExtractor.php';
require './fake_vendor/CSVMapper/Bootstrapper/ReflectorPropSearcher.php';
require './fake_vendor/CSVMapper/Bootstrapper/ReflectorInjector.php';
require './fake_vendor/CSVMapper/Bootstrapper/CSVMapperInjector.php';
