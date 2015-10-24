<?php
/**
 * Copyright (c) 2015 Robin Appelman <icewind@owncloud.com>
 * This file is licensed under the Licensed under the MIT license:
 * http://opensource.org/licenses/MIT
 */

namespace Icewind\Replay\Tests\Recorder;

use Icewind\Replay\Player\FunctionPlayer;
use Icewind\Replay\Record\JsonRecord;
use Icewind\Replay\Record\Track;
use Icewind\Replay\Tests\PatcherTest;

class FunctionPlayerTest extends PatcherTest {
	public function setUp() {
		parent::setUp();
		require_once __DIR__ . '/../data/exceptionDummyFunction.php';
	}

	private function createTrack($name, $result) {
		return new Track(Track::TYPE_FUNCTION, microtime(true), microtime(true), $name, $result);
	}

	public function testBasicPlayback() {
		$record = new JsonRecord();
		$record->addTrack($this->createTrack('time', 100));

		$player = new FunctionPlayer($record);
		$player->attach($this->patcher);

		/** @var callable $method */
		$method = include '../data/simplePlayBack.php';
		$result = $method();

		$this->assertEquals(100, $result);
	}

	public function testPlayException() {
		$record = new JsonRecord();
		$record->addTrack($this->createTrack('dummy', new \Exception('asd')));

		$player = new FunctionPlayer($record);
		$player->attach($this->patcher);
		$player->attachFunction($this->patcher, 'dummy');

		/** @var callable $method */
		$method = include '../data/exceptionPlayBack.php';
		try {
			$method();
			$this->fail('Expected exception');
		} catch (\Exception $e) {
			$this->assertEquals('asd', $e->getMessage());
		}
	}

	public function testMultiplePlayback() {
		$record = new JsonRecord();
		$record->addTrack($this->createTrack('time', 100));
		$record->addTrack($this->createTrack('time', 150));
		$record->addTrack($this->createTrack('dummy', 'asd'));

		$player = new FunctionPlayer($record);
		$player->attach($this->patcher);
		$player->attachFunction($this->patcher, 'dummy');

		/** @var callable $method */
		$method = include '../data/multipleFunctions.php';
		$result = $method();

		$this->assertEquals([100, 150, 'asd'], $result);
	}

	/**
	 * @expectedException \Icewind\Replay\Exceptions\UnexpectedCallException
	 */
	public function testUnexpectedCall() {
		$record = new JsonRecord();
		$record->addTrack($this->createTrack('time', 100));

		$player = new FunctionPlayer($record);
		$player->attach($this->patcher);

		/** @var callable $method */
		$method = include '../data/unexpectedCall.php';
		$result = $method();
	}
}
