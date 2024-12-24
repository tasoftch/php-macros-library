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

class SimpleMacro extends AbstractMacro
{
	/** @var null|string|callable */
	private $symbolNotFound;

	/**
	 * @param array $substitutions
	 */
	public function __construct(array $substitutions = [])
	{
		foreach($substitutions as $key => $substitution) {
			if(is_string($key)) {
				$this->setSubstitution($key, $substitution);
			}
		}
	}

	public function setSymbolNotFound($symbolNotFound): SimpleMacro
	{
		$this->symbolNotFound = $symbolNotFound;
		return $this;
	}

	protected function symbolNodFoundMacro($expression, $symbol): string
	{
		if(is_string($this->symbolNotFound))
			return $this->symbolNotFound;

		if(is_callable($this->symbolNotFound))
			return($this->symbolNotFound)($expression, $symbol);

		return parent::symbolNodFoundMacro($expression, $symbol);
	}
}