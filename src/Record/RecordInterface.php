<?php
/**
 * Copyright (c) 2015 Robin Appelman <icewind@owncloud.com>
 * This file is licensed under the Licensed under the MIT license:
 * http://opensource.org/licenses/MIT
 */

namespace Icewind\Replay\Record;

interface RecordInterface {
	/**
	 * @return Track|null
	 */
	public function readTrack();

	/**
	 * @param Track $track
	 */
	public function addTrack(Track $track);

	/**
	 * @return Track[]
	 */
	public function getTracks();
}
