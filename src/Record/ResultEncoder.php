<?php
/**
 * Copyright (c) 2015 Robin Appelman <icewind@owncloud.com>
 * This file is licensed under the Licensed under the MIT license:
 * http://opensource.org/licenses/MIT
 */

namespace Icewind\Replay\Record;

class ResultEncoder {
	const KEY_TYPE = '__encoded_type__';
	const TYPE_RESOURCE = 'resource';
	const TYPE_EXCEPTION = 'exception';

	/**
	 * Encode a result into something we can store
	 *
	 * @param mixed $result
	 * @return array|float|int|string|bool
	 */
	public function encode($result) {
		if (is_resource($result)) {
			return $this->encodeResource($result);
		} else if ($result instanceof \Exception) {
			return $this->encodeException($result);
		} else {
			return $result;
		}
	}

	/**
	 * @param resource $resource
	 * @return array
	 */
	private function encodeResource($resource) {
		return [
			self::KEY_TYPE => self::TYPE_RESOURCE,
			'value' => (string)$resource
		];
	}

	/**
	 * @param \Exception $exception
	 * @return array
	 */
	private function encodeException($exception) {
		$reflectionClass = new \ReflectionClass('\Exception');
		$reflectionProperty = $reflectionClass->getProperty('trace');
		$reflectionProperty->setAccessible(true);
		$reflectionProperty->setValue($exception, null);
		return [
			self::KEY_TYPE => self::TYPE_EXCEPTION,
			'value' => serialize($exception)
		];
	}

	/**
	 * @param array|float|int|string|bool $result
	 * @return mixed
	 */
	public function decode($result) {
		if (is_array($result) && isset($result[self::KEY_TYPE])) {
			return $this->decodeValue($result[self::KEY_TYPE], $result['value']);
		} else {
			return $result;
		}
	}

	/**
	 * @param string $type
	 * @param string $value
	 * @return mixed
	 */
	private function decodeValue($type, $value) {
		switch ($type) {
			case self::TYPE_RESOURCE:
				// we cant create a resource, just return the string since we overwrite any function using the resource anyway
				return $value;
			case self::TYPE_EXCEPTION:
				return $this->unserializeException($value);
			default:
				return $value;
		}
	}

	/**
	 * @param string $value
	 * @return \Exception
	 */
	private function unserializeException($value) {
		/** @var \Exception $exception */
		$exception = unserialize($value);

		// serializing does not preserve a null value for 'previous' so we restore it manually
		if (!$exception->getPrevious()) {
			$reflectionClass = new \ReflectionClass('\Exception');
			$reflectionProperty = $reflectionClass->getProperty('previous');
			$reflectionProperty->setAccessible(true);
			$reflectionProperty->setValue($exception, null);
		}
		return $exception;
	}
}
