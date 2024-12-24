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
use TASoft\Macro\SimpleMacro;
use TASoft\Macro\SimpleRecursiveMacro;

class MacroTest extends TestCase
{
	public function testSimpleStringMakro() {
		$mk = new SimpleMacro([
			'TEST' => 23,
			'HALLO' => 'Welt!'
		]);

		$this->assertEquals("Das ist 23. Meine Welt!", $mk->macroString("Das ist $(TEST). Meine $(HALLO)"));

		$this->assertEquals("Heio $(NICHT).", $mk->macroString("Heio $(NICHT)."));
		$this->assertEquals("Heio $(unkonf. name è).", $mk->macroString("Heio $(unkonf. name è)."));

		$mk->setSymbolNotFound("Nö");

		$this->assertEquals("Heio Nö.", $mk->macroString("Heio $(NICHT)."));
		$this->assertEquals("Heio $(unkonf. name è).", $mk->macroString("Heio $(unkonf. name è)."));

		$mk->setSymbolNotFound(function($e, $s) {
			return "NEIN($s)";
		});

		$this->assertEquals("Heio NEIN(NICHT).", $mk->macroString("Heio $(NICHT)."));
		$this->assertEquals("Heio $(unkonf. name è).", $mk->macroString("Heio $(unkonf. name è)."));

		$mk->setSymbolNotFound(NULL);
		$this->assertEquals("Heio $(NICHT).", $mk->macroString("Heio $(NICHT)."));
		$this->assertEquals("Heio $(unkonf. name è).", $mk->macroString("Heio $(unkonf. name è)."));
	}

	public function testRecursiveStringMakro() {
		$mk = new SimpleRecursiveMacro([
			'TEST_1' => 'Hier kommt $(TEST_2)',
			'TEST_2' => 'Thomas mit $(TEST_3)',
			'TEST_3' => 'sonst noch $(TEST_4)',
			"TEST_5" => 'Problem $(PROBLEM)',
			'PROBLEM' => ' jaja so $(TEST_5)'
		]);

		$this->assertEquals("Jetzt: Hier kommt Thomas mit sonst noch $(TEST_4)", $mk->macroString("Jetzt: $(TEST_1)"));

		$this->expectException(\TASoft\Macro\Exception\CircularSubstitutionException::class);
		$this->assertEquals("", $mk->macroString("Problem: $(TEST_5)"));
	}
}
