<?php
/**
 * Copyright (c) 2015 Robin Appelman <icewind@owncloud.com>
 * This file is licensed under the Licensed under the MIT license:
 * http://opensource.org/licenses/MIT
 */

namespace Icewind\Replay\Record;

class Track {
	const TYPE_FUNCTION = 1;
	const TYPE_METHOD = 2;
	const TYPE_STATIC_METHOD = 3;

	/**
	 * @var int any of the self::TYPE_ constants
	 */
	private $type;

	/**
	 * @var float
	 */
	private $start;

	/**
	 * @var float
	 */
	private $end;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var mixed
	 */
	private $result;

	/**
	 * Track constructor.
	 *
	 * @param int $type
	 * @param float $start
	 * @param float $end
	 * @param string $name
	 * @param mixed $result
	 */
	public function __construct($type, $start, $end, $name, $result) {
		$this->type = $type;
		$this->start = $start;
		$this->end = $end;
		$this->name = $name;
		$this->result = $result;
	}

	/**
	 * any of the self::TYPE_ constants
	 *
	 * @return int
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * The start time of the call
	 *
	 * @return float
	 */
	public function getStart() {
		return $this->start;
	}

	/**
	 * The end time of the call
	 *
	 * @return float
	 */
	public function getEnd() {
		return $this->end;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return mixed
	 */
	public function getResult() {
		return $this->result;
	}
}
