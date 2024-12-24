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

namespace TASoft\Macro\Subs;

use TASoft\Macro\Subs\AbstractConstantSubstitution;

class LocalIP extends AbstractConstantSubstitution
{

	public function toString($options = NULL): ?string
	{
		$localIP = null;

		if (function_exists('shell_exec')) {
			if (strncasecmp(PHP_OS, 'WIN', 3) == 0) {
				$output = shell_exec('ipconfig');
				if ($output) {
					preg_match('/IPv4-Adresse.*?:\s*([\d\.]+)/', $output, $matches);
					$localIP = $matches[1] ?? null;
				}
			} else {
				$output = shell_exec('ifconfig 2>/dev/null') ?: shell_exec('ip addr 2>/dev/null');
				if ($output) {
					preg_match_all('/inet\s([\d\.]+).*?broadcast|inet\s([\d\.]+)/', $output, $matches);
					foreach($matches[1] as $ip) {
						if($ip) {
							$localIP = $ip;
							break;
						}
					}

					if(!$localIP) {
						foreach($matches[2] as $ip) {
							if($ip) {
								$localIP = $ip;
								break;
							}
						}
					}
				}
			}
		}

		if (!$localIP && isset($_SERVER['SERVER_ADDR'])) {
			$localIP = $_SERVER['SERVER_ADDR'];
		}
		return $localIP ?: "0.0.0.0";
	}
}