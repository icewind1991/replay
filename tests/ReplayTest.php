<?php
/**
 * Copyright (c) 2015 Robin Appelman <icewind@owncloud.com>
 * This file is licensed under the Licensed under the MIT license:
 * http://opensource.org/licenses/MIT
 */

namespace Icewind\Replay\Tests;

use Icewind\Replay\Record\JsonRecord;
use Icewind\Replay\Replay;

class ReplayTest extends TestCase {
	/**
	 * @outputBuffering
	 */
	public function testRecord() {
		$replay = new Replay();
		$before = time();
		$record = $replay->record(__DIR__ . '/data/replayTest.php');
		$after = time();
		$tracks = $record->getTracks();
		$this->assertCount(1, $tracks);
		$track = $tracks[0];
		$this->assertGreaterThanOrEqual($before, $track->getResult());
		$this->assertLessThanOrEqual($after, $track->getResult());
	}

	public function testPlay() {
		$replay = new Replay();
		$record = new JsonRecord(__DIR__ . '/data/simpleRecord.json');
		$this->expectOutputString("1445698324");
		$replay->play(__DIR__ . '/data/replayTest2.php', $record);
	}
}
