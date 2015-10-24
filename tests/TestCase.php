<?php
/**
 * Copyright (c) 2015 Robin Appelman <icewind@owncloud.com>
 * This file is licensed under the Licensed under the MIT license:
 * http://opensource.org/licenses/MIT
 */

namespace Icewind\Replay\Tests;

abstract class TestCase extends \PHPUnit_Framework_TestCase {
	private $tmpFiles = [];

	public function tearDown() {
		parent::tearDown();
		foreach ($this->tmpFiles as $file) {
			if (is_file($file)) {
				unlink($file);
			}
		}
	}

	protected function tempNam($postFix = '') {
		$id = uniqid();
		$file = tempnam(sys_get_temp_dir(), $id) . $postFix;
		$tmpFiles[] = $file;
		return $file;
	}
}
