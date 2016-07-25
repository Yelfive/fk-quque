<?php

/**
 * @author Felix Huang <yelfivehuang@gmail.com>
 */

namespace fk\queue\commands;

use yii\base\InvalidConfigException;

/**
 * @property array|string $command
 * ```php
 * $command = ['match/clean', 'user', 'tag']
 * $command = 'match/clean user tag'
 * ```
 */
class YiiCommand extends Command
{

    public $yiiPath = '';

    public $encodeType = 'json';

    public function parse(): array
    {
        if (!$this->yiiPath) {
            $this->yiiPath = dirname(\Yii::$app->getBasePath());
        }
//        \Yii::error($this->command);
        if (is_array($this->command)) {
            $command = array_shift($this->command);
            foreach ($this->command as $v) {
                $command .= ' "' . addslashes(is_string($v) ? $v : $this->encode($v)) . '"';
            }
//            \Yii::error([$this->command, $command]);
        } else if (is_string($this->command)) {
            $command = $this->command;
        } else {
            throw new \Exception('Invalid yii command type.');
        }

        $command = "php $this->yiiPath/yii $command";

        return [$command];
    }

    protected function encode($data)
    {
        switch ($this->encodeType) {
            case 'json':
                return json_encode($data, JSON_UNESCAPED_UNICODE);
            default:
                throw new InvalidConfigException(__CLASS__ . '::$encodeType not supported: ' . $this->encodeType);
        }

    }
}