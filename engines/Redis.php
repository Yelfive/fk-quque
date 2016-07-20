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
     * @param array $cmd Cmd to be applied
     * @return bool Whether get in queue successfully
     */
    public function in(array $cmd): bool
    {
        \Yii::$app->redis->rpush($this->key, implode("\n", $cmd));
    }

    /**
     * @return string
     */
    public function out(): string
    {
        return \Yii::$app->redis->lpop($this->key);
    }

    public function execute()
    {
        $cmd = $this->out();
        exec($cmd, $result, $status);
        var_dump($result, $status);
        die;
    }

}