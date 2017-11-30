<?php

/**
 * @author Felix Huang <yelfivehuang@gmail.com>
 * @date 2017-06-11
 */

namespace fk\queue\wrapper\laravel;

class Connection extends \fk\queue\Connection
{
    public function __construct()
    {
        $this->logPath = base_path($this->config('logPath'));
    }

    protected function config($name)
    {
        return config("queue.$name");
    }
}