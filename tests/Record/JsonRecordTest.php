<?php
/**
 * Copyright (c) 2015 Robin Appelman <icewind@owncloud.com>
 * This file is licensed under the Licensed under the MIT license:
 * http://opensource.org/licenses/MIT
 */

namespace Icewind\Replay\Tests\Recorder;

use Icewind\Replay\Record\JsonRecord;
use Icewind\Replay\Record\Track;
use Icewind\Replay\Tests\TestCase;

class JsonRecordTest extends TestCase {
	public function trackProvider() {
		return [
			[[
				new Track(Track::TYPE_FUNCTION, 100, 100.1, 'foo', 1),
				new Track(Track::TYPE_FUNCTION, 100.1, 100.2, 'bar', 'asd'),
				new Track(Track::TYPE_FUNCTION, 100.1, 100.2, 'asd', null),
				new Track(Track::TYPE_FUNCTION, 100.1, 100.2, 'asd', false),
				new Track(Track::TYPE_FUNCTION, 100.2, 100.3, 'qw', new \InvalidArgumentException('asd')),
			]],
			[[
				new Track(Track::TYPE_FUNCTION, 100, 100.1, 'foo', 1)
			]]
		];
	}

	/**
	 * @dataProvider trackProvider
	 * @param Track[] $tracks
	 */
	public function testSaveLoadRecord($tracks) {
		$record = new JsonRecord();
		foreach ($tracks as $track) {
			$record->addTrack($track);
		}

		$file = $this->tempNam('.json');
		$record->save($file);

		$loaded = new JsonRecord($file);
		$this->assertEquals($tracks, $loaded->getTracks());
	}

	/**
	 * @dataProvider trackProvider
	 * @param Track[] $tracks
	 */
	public function testAddReadTrack($tracks) {
		$record = new JsonRecord();
		foreach ($tracks as $track) {
			$record->addTrack($track);
		}

		$result = [];
		while ($track = $record->readTrack()) {
			$result[] = $track;
		}

		$this->assertEquals(count($tracks), count($result));
		foreach ($tracks as $i => $entry) {
			$this->assertEquals($result[$i], $entry);
		}
	}
}
