# Replay

[![Build Status](https://travis-ci.org/icewind1991/replay.svg?branch=master)](https://travis-ci.org/icewind1991/replay)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/icewind1991/replay/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/icewind1991/replay/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/icewind1991/replay/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/icewind1991/replay/?branch=master)

Record and replay php requests

```
composer require icewind/replay
```

Records input from builtin php functions to allow replaying the request from the exact same state.

Note, **only** code using namespaces can be replayed

## Usage

### Record

```
php rec.php script.php new_recording.json
```

Or 

```php
require 'vendor/autoload.php';

$replay = new \Icewind\Replay\Replay();
$record = $replay->record('script.php');
$record->save('new_recording.json');
```

### Playback

```
php play.php script.php existing_recording.json
```

Or

```php
require 'vendor/autoload.php';

$replay = new \Icewind\Replay\Replay();
$record = new \Icewind\Replay\Record\JsonRecord('existing_recording.json');
$record = $replay->play('script.php', $record);
```

## TODO

- Support recording/replaying input from builtin classes (PDO)
