<?php

/**
 * @author Felix Huang <yelfivehuang@gmail.com>
 */

namespace fk\queue\commands;

use yii\base\InvalidConfigException;

/**
 * @property array|string $command
 * ```php
 * // php yii match/clean user tag
 * $command = ['match/clean', 'user', 'tag']
 *
 * // also can be passed like this
 * $command = 'match/clean user tag'
 *
 * // If the param is an array, it will be json_encoded
 * // php yii match/clean "{\"user\"=>1,\"created_by\"=>2}"
 * $command = ['match/clean', ['user' => '1', 'created_by' => 2]]
 *
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
        if (is_array($this->command)) {
            $command = array_shift($this->command);
            foreach ($this->command as $v) {
                $command .= ' "' . addslashes(is_string($v) ? $v : $this->encode($v)) . '"';
            }
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