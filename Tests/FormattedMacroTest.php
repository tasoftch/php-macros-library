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
use TASoft\Macro\FormattedMacro;
use TASoft\Macro\Formatter\CamelcaseFormatter;
use TASoft\Macro\Formatter\LowercaseFormatter;
use TASoft\Macro\Formatter\PrintFormatter;
use TASoft\Macro\Formatter\UppercaseFormatter;
use TASoft\Macro\Formatter\UppercaseWordsFormatter;

class FormattedMacroTest extends TestCase
{
	public function testFormattedMacro() {
		$mk = new FormattedMacro([
			'PI' => M_PI
		]);

		$mk->setFormatter(new PrintFormatter("%.2f"), 'PI');

		$this->assertEquals("Das ist Pi: 3.14", $mk("Das ist Pi: $(PI)"));

		$mk->setSubstitution("NAME", 'ThOMaS-kommt NACH Hause. Dann geht er weiter.');
		$mk->setSubstitution("USER", 'ThOMaS-kommt NACH Hause. Dann geht er weiter.');
		$mk->setSubstitution("BEST", 'ThOMaS-kommt NACH Hause. Dann geht er weiter.');
		$mk->setSubstitution("HEIN", 'ThOMaS-kommt NACH Hause. Dann geht er weiter.');

		$mk->setFormatter(new UppercaseFormatter(), 'NAME');
		$mk->setFormatter(new LowercaseFormatter(), 'USER');
		$mk->setFormatter(new CamelcaseFormatter(), 'BEST');
		$mk->setFormatter(new UppercaseWordsFormatter(), 'HEIN');

		$this->assertEquals("THOMAS-KOMMT NACH HAUSE. DANN GEHT ER WEITER.", $mk("$(NAME)"));
		$this->assertEquals("thomas-kommt nach hause. dann geht er weiter.", $mk("$(USER)"));
		$this->assertEquals("ThOMaS-kommt NACH Hause. Dann Geht Er Weiter.", $mk("$(HEIN)"));
		$this->assertEquals("thomasKommtNachHauseDannGehtErWeiter", $mk("$(BEST)"));

		$mk->setFormatters(new LowercaseFormatter(), ['NAME', 'USER', 'BEST']);

		$this->assertEquals("thomas-kommt nach hause. dann geht er weiter.", $mk("$(NAME)"));
		$this->assertEquals("thomas-kommt nach hause. dann geht er weiter.", $mk("$(USER)"));
		$this->assertEquals("ThOMaS-kommt NACH Hause. Dann Geht Er Weiter.", $mk("$(HEIN)"));
		$this->assertEquals("thomas-kommt nach hause. dann geht er weiter.", $mk("$(BEST)"));
	}
}
