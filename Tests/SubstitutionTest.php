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

use PHPUnit\Framework\TestCase;
use TASoft\Macro\Subs\CallbackSubstitution;
use TASoft\Macro\Subs\LocalIP;
use TASoft\Macro\Subs\StaticSubstitution;
use TASoft\Macro\Subs\UserName;

class SubstitutionTest extends TestCase
{
	public function testStaticSubstitution() {
		$subs = new StaticSubstitution();

		$this->assertNull($subs->toString());
		$this->assertEquals("", (string) $subs);

		$subs->setValue("Thomas");

		$this->assertEquals("Thomas", $subs);
	}

	public function testCallbackSubstitution() {
		$subs = new CallbackSubstitution(function($options) use (&$val, &$opts) {
			$opts = $options;
			return $val;
		});

		$val = 78;
		$this->assertEquals("78", $subs);

		$val = 'Test';
		$this->assertEquals("Test", $subs->toString('Hehe'));
		$this->assertEquals("Hehe", $opts);
	}

	public function testConstantSubstitutions() {
		$this->assertEquals("thomas", new UserName());
		$this->assertEquals("192.168.86.170", (string) new LocalIP());

	}
}
