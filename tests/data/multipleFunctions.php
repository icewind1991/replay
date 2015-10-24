<?php

namespace Foo3;

return function () {
	$results = [];
	$results[] = time();
	$results[] = time();
	$results[] = dummy();
	return $results;
};
