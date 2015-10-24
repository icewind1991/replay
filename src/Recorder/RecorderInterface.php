<?php
/**
 * Copyright (c) 2015 Robin Appelman <icewind@owncloud.com>
 * This file is licensed under the Licensed under the MIT license:
 * http://opensource.org/licenses/MIT
 */

namespace Icewind\Replay\Recorder;

use Icewind\Patcher\Patcher;
use Icewind\Replay\Record\RecordInterface;

interface RecorderInterface {
	public function setRecord(RecordInterface $record);

	public function attach(Patcher $patcher);
}
