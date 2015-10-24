<?php
/**
 * Copyright (c) 2015 Robin Appelman <icewind@owncloud.com>
 * This file is licensed under the Licensed under the MIT license:
 * http://opensource.org/licenses/MIT
 */

namespace Icewind\Replay\Record;

class JsonRecord implements RecordInterface {
	private $tracks = [];

	private $version = 1;

	/**
	 * @var ResultEncoder
	 */
	private $encoder;

	private $readPointer = 0;

	/**
	 * @param null|string $file
	 */
	public function __construct($file = null) {
		$this->encoder = new ResultEncoder();
		if (!is_null($file)) {
			$this->load($file);
		}
	}

	/**
	 * @param string $file
	 */
	private function load($file) {
		$content = file_get_contents($file);
		$data = json_decode($content, true);
		$this->version = $data['version'];
		$this->tracks = array_map([$this, 'decodeTrack'], $data['tracks']);
	}

	/**
	 * @param array $entry
	 * @return Track
	 */
	private function decodeTrack($entry) {
		return new Track(
			$entry['type'],
			$entry['start'],
			$entry['end'],
			$entry['name'],
			$this->encoder->decode($entry['result'])
		);
	}

	/**
	 * @param string $file
	 */
	public function save($file) {
		$data = [
			'version' => $this->version,
			'tracks' => array_map([$this, 'encodeTrack'], $this->tracks)
		];
		$content = json_encode($data);
		file_put_contents($file, $content);
	}

	/**
	 * @param Track $track
	 * @return array
	 */
	private function encodeTrack(Track $track) {
		return [
			'type' => $track->getType(),
			'start' => $track->getStart(),
			'end' => $track->getEnd(),
			'name' => $track->getName(),
			'result' => $this->encoder->encode($track->getResult())
		];
	}

	/**
	 * @return Track|null
	 */
	public function readTrack() {
		if ($this->readPointer < count($this->tracks)) {
			return $this->tracks[$this->readPointer++];
		} else {
			return null;
		}
	}

	/**
	 * @param Track $track
	 */
	public function addTrack(Track $track) {
		$this->tracks[] = $track;
	}

	/**
	 * @return Track[]
	 */
	public function getTracks() {
		return $this->tracks;
	}
}
