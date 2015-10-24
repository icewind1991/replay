<?php
/**
 * Copyright (c) 2015 Robin Appelman <icewind@owncloud.com>
 * This file is licensed under the Licensed under the MIT license:
 * http://opensource.org/licenses/MIT
 */

namespace Icewind\Replay\Tests\Recorder;

use Icewind\Replay\Record\JsonRecord;
use Icewind\Replay\Recorder\FunctionRecorder;
use Icewind\Replay\Tests\PatcherTest;

class FunctionRecorderTest extends PatcherTest {
	public function testBasicRecord() {
		$record = new JsonRecord();
		$recorder = new FunctionRecorder($record);
		$recorder->attach($this->patcher);

		/** @var callable $method */
		$method = include '../data/simpleFunction.php';
		$result = $method();
		$tracks = $record->getTracks();

		$this->assertCount(1, $tracks);
		$this->assertEquals('time', $tracks[0]->getName());
		$this->assertEquals($result, $tracks[0]->getResult());
	}

	public function testRecordException() {
		$record = new JsonRecord();
		$recorder = new FunctionRecorder($record);
		$recorder->attach($this->patcher);
		$recorder->attachFunction($this->patcher, 'dummy');

		require_once '../data/exceptionDummyFunction.php';
		/** @var callable $method */
		$method = include '../data/exceptionFunction.php';
		try {
			$method();
		} catch (\Exception $e) {

		}
		$tracks = $record->getTracks();

		$this->assertCount(1, $tracks);
		$this->assertEquals('dummy', $tracks[0]->getName());
		$this->assertInstanceOf('\Exception', $tracks[0]->getResult());
		/** @var \Exception $e */
		$e = $tracks[0]->getResult();
		$this->assertEquals('asd', $e->getMessage());
	}
}
