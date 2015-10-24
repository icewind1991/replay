<?php

if (!function_exists('\dummy')) {
	function dummy() {
		throw new \Exception('asd');
	}
}
