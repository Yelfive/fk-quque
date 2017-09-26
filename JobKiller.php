<?php

/**
 * @author Felix Huang <yelfivehuang@gmail.com>
 * @date 2017-09-26
 */

namespace fk\queue;

class JobKiller
{

    /**
     * Command for the queue, the use as the
     * @var string
     */
    public $cmd = 'php yii queue/start';

    /**
     * @var array
     */
    protected $queuePIDs = [];

    public function __construct()
    {
        $this->queuePIDs = $this->findQueueIDs();
    }

    public static function watch($timeout)
    {
        $sleep = intval($timeout / 2); // sleep for half of the expire time
        if ($sleep < 60) $sleep = 60; // sleep at least 60 seconds

        while (true) {
            $killer = new static;
            $ids = $killer->findExpired($killer->queuePIDs, $timeout);
            if ($ids) exec('kill ' . implode(' ', $ids));
            sleep($sleep);
        }
    }

    public function findQueueIDs(): array
    {
        $header = exec('ps aux | head -n 1');
        $PIDIndex = array_search('PID', $this->fields($header));

        exec("ps aux|grep -E '$this->cmd'", $lines);
        array_pop($lines);
        array_pop($lines);
        $ids = [];
        foreach ($lines as $line) {
            $ids[] = $this->fields($line, $PIDIndex);
        }
        return $ids;
    }

    public function findExpired($parentIDs, $timeout)
    {
        $output = exec('ps lax | head -n 1');
        $header = $this->fields($output);
        $PIDIndex = array_search('PID', $header);
        $PPIDIndex = array_search('PPID', $header);
        exec('ps lax| grep php', $lines);

        $targetIDs = [];
        foreach ($lines as $line) {
            $fields = $this->fields($line);
            $PPID = $fields[$PPIDIndex];
            if (!in_array($PPID, $parentIDs)) continue;

            $PID = $fields[$PIDIndex];
            $start = strtotime(exec("ps -o start -p $PID"));
            $current = time();
            if ($current - $start > $timeout) {
                $targetIDs[] = $PID;
            }
        }
        if ($targetIDs) $targetIDs = array_merge($targetIDs, $this->findExpired($targetIDs, $timeout));
        return $targetIDs;
    }

    protected function fields($line, $index = null)
    {
        preg_match_all('/\S+/', $line, $matches);
        return $index === null ? $matches[0] : ($matches[0][$index] ?? []);
    }
}

