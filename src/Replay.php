<?php
/**
 * Copyright (c) 2015 Robin Appelman <icewind@owncloud.com>
 * This file is licensed under the Licensed under the MIT license:
 * http://opensource.org/licenses/MIT
 */

namespace Icewind\Replay;

use Icewind\Patcher\Patcher;
use Icewind\Replay\Player\FunctionPlayer;
use Icewind\Replay\Player\PlayerInterface;
use Icewind\Replay\Record\JsonRecord;
use Icewind\Replay\Record\RecordInterface;
use Icewind\Replay\Recorder\FunctionRecorder;
use Icewind\Replay\Recorder\RecorderInterface;

class Replay {
	/**
	 * @param string $file the php script to record
	 * @return JsonRecord
	 */
	public function record($file) {
		$record = new JsonRecord();
		$recorders = $this->getRecorders($record);
		$this->runPatched($file, $recorders);
		return $record;
	}

	/**
	 * @param string $file
	 * @param RecordInterface $record the record to play back
	 */
	public function play($file, RecordInterface $record) {
		$players = $this->getPlayers($record);
		$this->runPatched($file, $players);
	}

	/**
	 * @param $file
	 * @param RecorderInterface[]|PlayerInterface[] $listeners
	 */
	private function runPatched($file, array $listeners) {
		$patcher = $this->getPatcher();
		foreach ($listeners as $listener) {
			$listener->attach($patcher);
		}
		$patcher->autoPatch();

		include $file;

		$patcher->__destruct(); //ensure we clean up
	}

	private function getPatcher() {
		$patcher = new Patcher();

		// white list everything except ourselves and our dependencies
		$patcher->whiteListDirectory('/');

		$patcher->blackListDirectory(__DIR__); // our own source
		$patcher->blackListDirectory(dirname(__DIR__) . '/icewind'); // interceptor and patcher when installed as composer dependency
		$patcher->blackListDirectory(dirname(__DIR__) . '/vendor'); // interceptor and patcher when not as dependency

		return $patcher;
	}

	/**
	 * @param RecordInterface $record
	 * @return RecorderInterface[]
	 */
	private function getRecorders(RecordInterface $record) {
		return [
			new FunctionRecorder($record)
		];
	}

	/**
	 * @param RecordInterface $record
	 * @return PlayerInterface[]
	 */
	private function getPlayers(RecordInterface $record) {
		return [
			new FunctionPlayer($record)
		];
	}
}
