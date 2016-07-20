<?php

/**
 * @author Felix Huang <yelfivehuang@gmail.com>
 */

namespace fk\queue\commands;

use yii\base\InvalidCallException;

abstract class Command
{

    protected $command = [];

    public function __construct($cmd)
    {
        $this->command = $cmd;
    }

    public function parse(): array
    {
        throw new InvalidCallException(__METHOD__ . ' must be overwrite.');
    }
}