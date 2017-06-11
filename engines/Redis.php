<?php

/**
 * @author Felix Huang <yelfivehuang@gmail.com>
 */

namespace fk\queue\engines;

class Redis implements EngineInterface
{

    public $key = 'queue';

    protected $object;

    /**
     * @param string $cmd Cmd to be applied
     * @param int $delay
     * @return bool Whether get in queue successfully
     */
    public function in(string $cmd, $delay = 0): bool
    {
        \Yii::$app->redis->rpush($this->key, $cmd);
        return true;
    }

    /**
     * @return string
     */
    public function out(): string
    {
        return (string)\Yii::$app->redis->lpop($this->key);
    }

    public function get()
    {
    }

    public function remove($id)
    {
    }
}