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

use TASoft\Macro\Formatter\FormatterInterface;

trait MacroFormatterContainerTrait
{
	protected $formatters = [];

	public function setFormatter(FormatterInterface $formatter, $name)
	{
		if(array_key_exists($name, $this->formatters))
			trigger_error("Formatter $name already exists", E_USER_WARNING);
		$this->formatters[$name] = $formatter;
	}

	public function setFormatters(FormatterInterface $formatter, array $names) {
		foreach($names as $name)
			$this->setFormatter($formatter, $name);
	}

	/**
	 * Returns the substitution for a name.
	 *
	 * @param string $name
	 * @return FormatterInterface|null
	 */
	public function getFormatter(string $name): ?FormatterInterface {
		return $this->formatters[$name] ?? NULL;
	}

	/**
	 * Returns a ready string
	 *
	 * @param string $name
	 * @param $context
	 * @return string|null
	 */
	public function getFormattedValue($value, string $name, $context = NULL): ?string
	{
		$fmt = $this->getFormatter($name);
		if($fmt instanceof FormatterInterface)
			$value = $fmt->formatValue($value, $context);
		return $value;
	}
}