<?php

/**
 * @author Felix Huang <yelfivehuang@gmail.com>
 */

namespace fk\queue\commands;

/**
 * @property array|string $command
 * ```php
 * $command = ['match/clean', 'user', 'tag']
 * $command = 'match/clean user tag'
 * ```
 */
class YiiCommand extends Command
{

    public $yiiPath;

    public function parse(): array
    {
        $this->yiiPath;
        if (is_array($this->command)) {
            $command = implode(' ', $this->command);
        } else if (is_string($this->command)) {
            $command = $this->command;
        } else {
            throw new \Exception('Invalid yii command type.');
        }
        return [$command];
    }
}