<?php
/**
 * Copyright (c) 2015 Robin Appelman <icewind@owncloud.com>
 * This file is licensed under the Licensed under the MIT license:
 * http://opensource.org/licenses/MIT
 */

namespace Icewind\Replay\Exceptions;

class UnexpectedCallException extends PlayBackException {
	public function __construct($expected, $actual) {
		parent::__construct('Unexpected call to ' . $actual . ' during playback, expected call to ' . $expected);
	}
}
