<?php
// usage php play.php script.php recording.json

require 'vendor/autoload.php';

$script = $argv[1];
$input = $argv[2];

$replay = new \Icewind\Replay\Replay();
$record = new \Icewind\Replay\Record\JsonRecord(__DIR__ . '/' . $input);

$replay->play($script, $record);
