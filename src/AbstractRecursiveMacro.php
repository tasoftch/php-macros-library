<?php
/*
 * Copyright (c) 2024 TASoft Applications, Th. Abplanalp <info@tasoft.ch>
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

namespace TASoft\Macro;

use TASoft\Macro\AbstractMacro;
use TASoft\Macro\Exception\CircularSubstitutionException;

class AbstractRecursiveMacro extends AbstractMacro
{
	private $recursion_protection_cache = [];

	protected function symbolCircularMacro($expression, $symbol): ?string
	{
		throw (new CircularSubstitutionException("Circular substitution for $symbol found"))->setSubstitution($symbol);
	}

	public function getSubstitutionString(string $name, $context = NULL): ?string
	{
		$value = parent::getSubstitutionString($name, $context);
		if(NULL !== $value)
			$this->recursion_protection_cache[] = $name;
		return $value;
	}

	private function _makroStringRecursive(string $string, $context, array $stack): string
	{
		$this->recursion_protection_cache = [];
		$string = parent::macroString($string, $context);

		if(!$this->recursion_protection_cache)
			return $string;

		foreach($this->recursion_protection_cache as $key) {
			if(in_array($key, $stack)) {
				$string = $this->symbolCircularMacro($string, $key);
			} else {
				$stack[] = $key;
			}
		}

		if(strpos($string, '$(') !== false) {
			$string = $this->_makroStringRecursive($string, $context, $stack);
		}
		return $string;
	}

	public function macroString(string $string, $context = NULL): string
	{
		return $this->_makroStringRecursive($string, $context, []);
	}
}