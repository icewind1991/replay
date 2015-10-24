<?php
/**
 * Copyright (c) 2015 Robin Appelman <icewind@owncloud.com>
 * This file is licensed under the Licensed under the MIT license:
 * http://opensource.org/licenses/MIT
 */

namespace Icewind\Replay\Tests;

use Icewind\Patcher\Patcher;

abstract class PatcherTest extends TestCase {
	/**
	 * @var Patcher
	 */
	protected $patcher;

	public function setUp() {
		parent::setUp();
		$this->patcher = new Patcher();
		$this->patcher->whiteListDirectory(__DIR__ . '/data');
		$this->patcher->autoPatch();
	}

	public function tearDown() {
		$this->patcher->__destruct();
	}
}
