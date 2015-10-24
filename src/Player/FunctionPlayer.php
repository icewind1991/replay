<?php
/**
 * Copyright (c) 2015 Robin Appelman <icewind@owncloud.com>
 * This file is licensed under the Licensed under the MIT license:
 * http://opensource.org/licenses/MIT
 */

namespace Icewind\Replay\Player;

use Icewind\Replay\Exceptions\UnexpectedCallException;
use Icewind\Replay\FunctionPatcher;

class FunctionPlayer extends FunctionPatcher {
	/**
	 * @param string $name
	 * @param array $arguments
	 * @param callable $original
	 * @return mixed
	 * @throws \Exception
	 */
	public function handler($name, array $arguments, callable $original) {
		$track = $this->record->readTrack();
		if ($track->getName() !== $name) {
			throw new UnexpectedCallException($track->getName(), $name);
		}
		if ($track->getResult() instanceof \Exception) {
			throw $track->getResult();
		} else {
			return $track->getResult();
		}
	}
}
