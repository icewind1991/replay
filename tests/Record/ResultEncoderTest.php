<?php
/**
 * Copyright (c) 2015 Robin Appelman <icewind@owncloud.com>
 * This file is licensed under the Licensed under the MIT license:
 * http://opensource.org/licenses/MIT
 */

namespace Icewind\Replay\Tests\Recorder;

use Icewind\Replay\Record\ResultEncoder;
use Icewind\Replay\Tests\TestCase;

class ResultEncoderTest extends TestCase {
	/**
	 * Values we dont need to encode
	 *
	 * @return array
	 */
	public function literalProvider() {
		return [
			[1],
			[null],
			[1.2],
			[false],
			[[1, 2, 3]],
			[['a' => 1, 'b' => 2]]
		];
	}

	/**
	 * @dataProvider literalProvider
	 * @param $literal
	 */
	public function testEncodeLiteral($literal) {
		$encoder = new ResultEncoder();
		$this->assertSame($literal, $encoder->encode($literal));
	}

	public function testEncodeResource() {
		$encoder = new ResultEncoder();
		$resource = fopen(__FILE__, 'r');
		$result = $encoder->encode($resource);
		fclose($resource);
		$this->assertSame(ResultEncoder::TYPE_RESOURCE, $result[ResultEncoder::KEY_TYPE]);
		$this->assertArrayHasKey('value', $result);
	}

	public function testEncodeException() {
		$encoder = new ResultEncoder();
		$ex = new \InvalidArgumentException();
		$result = $encoder->encode($ex);
		$this->assertSame(ResultEncoder::TYPE_EXCEPTION, $result[ResultEncoder::KEY_TYPE]);
		$this->assertArrayHasKey('value', $result);
	}

	/**
	 * @dataProvider literalProvider
	 * @param $literal
	 */
	public function testDecodeLiteral($literal) {
		$encoder = new ResultEncoder();
		$this->assertSame($literal, $encoder->decode($literal));
	}

	public function testDecodeUnkownType() {
		$encoder = new ResultEncoder();
		$this->assertSame(1, $encoder->decode([
			ResultEncoder::KEY_TYPE => 'dummy',
			'value' => 1
		]));
	}

	public function testDecodeException() {
		$encoder = new ResultEncoder();
		$ex = new \InvalidArgumentException('foo', 1);
		$result = $encoder->encode($ex);

		/** @var \InvalidArgumentException $decoded */
		$decoded = $encoder->decode($result);
		$this->assertInstanceOf('\InvalidArgumentException', $decoded);
		$this->assertSame('foo', $decoded->getMessage());
		$this->assertSame(1, $decoded->getCode());
	}

	public function testDecodeResource() {
		$encoder = new ResultEncoder();
		$resource = fopen(__FILE__, 'r');
		$result = $encoder->encode($resource);
		fclose($resource);

		$decoded = $encoder->decode($result);
		$this->assertStringStartsWith('Resource id #', $decoded);
	}
}
