<?php
/**
 * Copyright (c) 2015 Robin Appelman <icewind@owncloud.com>
 * This file is licensed under the Licensed under the MIT license:
 * http://opensource.org/licenses/MIT
 */

namespace Icewind\Replay;

use Icewind\Patcher\Patcher;
use Icewind\Replay\Record\RecordInterface;

abstract class FunctionPatcher {
	/**
	 * @var RecordInterface
	 */
	protected $record;

	public function __construct(RecordInterface $record) {
		$this->record = $record;
	}

	/**
	 * @return string[]
	 */
	private function getFunctionsToPatch() {
		return json_decode(file_get_contents(__DIR__ . '/data/functions.json'));
	}

	/**
	 * @param Patcher $patcher
	 */
	public function attach(Patcher $patcher) {
		$functions = $this->getFunctionsToPatch();

		foreach ($functions as $function) {
			$this->attachFunction($patcher, $function);
		}
	}

	/**
	 * @param Patcher $patcher
	 * @param string $function
	 */
	public function attachFunction(Patcher $patcher, $function) {
		$patcher->patchMethod($function, [$this, 'handler']);
	}

	/**
	 * @param string $name
	 * @param array $arguments
	 * @param callable $original
	 * @return mixed
	 * @throws \Exception
	 */
	abstract public function handler($name, array $arguments, callable $original);
}
