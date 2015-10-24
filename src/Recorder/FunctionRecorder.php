<?php
/**
 * Copyright (c) 2015 Robin Appelman <icewind@owncloud.com>
 * This file is licensed under the Licensed under the MIT license:
 * http://opensource.org/licenses/MIT
 */

namespace Icewind\Replay\Recorder;

use Icewind\Replay\FunctionPatcher;
use Icewind\Replay\Record\Track;

class FunctionRecorder extends FunctionPatcher {
	/**
	 * @param string $name
	 * @param array $arguments
	 * @param callable $original
	 * @return mixed
	 * @throws \Exception
	 */
	public function handler($name, array $arguments, callable $original) {
		$start = microtime(true);
		try {
			$result = $original();
		} catch (\Exception $e) {
			$end = microtime(true);
			$this->record($name, $start, $end, $e);
			throw $e;
		}
		$end = microtime(true);
		$this->record($name, $start, $end, $result);
		return $result;
	}

	private function record($name, $start, $end, $result) {
		$track = new Track(Track::TYPE_FUNCTION, $start, $end, $name, $result);
		$this->record->addTrack($track);
	}
}
