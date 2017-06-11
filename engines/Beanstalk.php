<?php

/**
 * @author Felix Huang <yelfivehuang@gmail.com>
 * @date 2017-06-09
 */

namespace fk\queue\engines;

use Pheanstalk\Pheanstalk;

class Beanstalk implements EngineInterface
{

    protected $driver;

    protected $tube;

    public function __construct($config)
    {
        // $host, $port, $tube
        /**
         * @var string $host
         * @var string $port
         * @var string $tube
         */
        extract($config);
        $this->driver = new Pheanstalk($host, $port);
        $this->tube = $tube;
    }

    /**
     * Method accepts array as only parameter,
     * and with this parameter,
     * each class implements this interface should write to cache or db
     * the cmd line in the string form
     * @param string $cmd Cmd to be applied
     * @param int $delay Seconds to wait before job becomes ready
     * @return bool Whether get in queue successfully
     */
    public function in(string $cmd, $delay = Pheanstalk::DEFAULT_DELAY): bool
    {
        return 0 < $this->driver
            ->useTube($this->tube)
            ->put(
                $cmd,
                Pheanstalk::DEFAULT_PRIORITY,
                $delay
            );
    }

    public function out()
    {
    }

    /**
     * @param int $timeout The timeout of wait for a job, if the time arrives, it will cease waiting
     * @return bool|object|\Pheanstalk\Job
     */
    public function get($timeout = 60)
    {
        return $this->driver
            ->watch($this->tube)
            ->reserve($timeout);
    }

    public function remove($job)
    {
        $this->driver->delete($job);
    }
}