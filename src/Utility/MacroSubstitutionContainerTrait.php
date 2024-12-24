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

namespace TASoft\Macro\Utility;

use TASoft\Macro\Subs\SubstitutionInterface;

trait MacroSubstitutionContainerTrait
{
	protected $substitutions = [];

	public function setSubstitution(string $name, $value)
	{
		if(array_key_exists($name, $this->substitutions))
			trigger_error("Substitution $name already exists", E_USER_WARNING);
		$this->substitutions[$name] = $value;
	}

	/**
	 * Returns the substitution for a name.
	 *
	 * @param string $name
	 * @return string|int|float|bool|null|SubstitutionInterface
	 */
	public function getSubstitution(string $name) {
		return $this->substitutions[$name] ?? NULL;
	}

	/**
	 * Returns a ready string
	 *
	 * @param string $name
	 * @param $context
	 * @return string|null
	 */
	public function getSubstitutionString(string $name, $context = NULL): ?string
	{
		$value = $this->getSubstitution($name);
		if($value instanceof SubstitutionInterface)
			$value = $value->toString($context);
		return $value;
	}
}