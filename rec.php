<?php
// usage php rec.php script.php recording.json

require 'vendor/autoload.php';

$script = $argv[1];
$output = $argv[2];

$replay = new \Icewind\Replay\Replay();

$record = $replay->record($script);

$record->save(__DIR__ . '/' . $output);
