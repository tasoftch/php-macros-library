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

abstract class AbstractMacroChain implements MacroInterface
{
	private $macros = [];

	public function addMacro(MacroInterface $macro, string $name) {
		$this->macros[$name] = $macro;
		return $this;
	}

	public function removeMacro($macro) {
		if(is_string($macro)) {
			if(isset($this->macros[$macro]))
				unset($this->macros[$macro]);
		} elseif(($idx = array_search($macro, $this->macros)) !== false) {
			unset($this->macros[$idx]);
		}
		return $this;
	}

	protected function shouldRunMacro(MacroInterface $macro, string $name, $context)
	{
		return true;
	}

	public function macroString(string $string, $context = NULL): string
	{
		/** @var MacroInterface $macro */
		foreach($this->macros as $name => $macro) {
			if($this->shouldRunMacro($macro, $name, $context))
				$string = $macro->macroString($string, $context);
		}
		return $string;
	}

	public function __invoke($string, $context=NULL)
	{
		return $this->macroString($string, $context);
	}
}