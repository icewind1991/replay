<?php
// usage php play.php script.php recording.json

require 'vendor/autoload.php';

$script = $argv[1];

$patcher = new \Icewind\Patcher\Patcher();
$patcher->whiteListDirectory(__DIR__);
$patcher->blackListDirectory(__DIR__ . '/src');
$record = new \Icewind\Replay\Record\JsonRecord(__DIR__ . '/' . $argv[2]);
$player = new \Icewind\Replay\Player\FunctionPlayer($record);
$player->attach($patcher);
$patcher->autoPatch();

require $script;
