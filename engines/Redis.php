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
     * @return bool Whether get in queue successfully
     */
    public function in(string $cmd): bool
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

}