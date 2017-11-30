<?php

/**
 * @author Felix Huang <yelfivehuang@gmail.com>
 * @date 2017-09-26
 */

include_once __DIR__ . '/../../../autoload.php';

$timeout = intval($argv[1] ?? 600);

if ($timeout <= 10) $timeout = 600; // 10 min

\fk\queue\JobKiller::watch($timeout);
