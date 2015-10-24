<?php
// usage php rec.php script.php recording.json

require 'vendor/autoload.php';

$script = $argv[1];

$patcher = new \Icewind\Patcher\Patcher();
$patcher->whiteListDirectory(__DIR__);
$patcher->blackListDirectory(__DIR__ . '/src');
$record = new \Icewind\Replay\Record\JsonRecord();
$recorder = new \Icewind\Replay\Recorder\FunctionRecorder($record);
$recorder->attach($patcher);
$patcher->autoPatch();

require $script;

$record->save(__DIR__ . '/' . $argv[2]);
