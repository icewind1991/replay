<?php
/**
 * Copyright (c) 2015 Robin Appelman <icewind@owncloud.com>
 * This file is licensed under the Licensed under the MIT license:
 * http://opensource.org/licenses/MIT
 */

namespace Icewind\Replay\Player;

use Icewind\Patcher\Patcher;
use Icewind\Replay\Record\RecordInterface;

interface PlayerInterface {
	public function attach(Patcher $patcher);
}
