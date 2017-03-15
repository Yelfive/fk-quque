<?php

/**
 * @author Felix Huang <yelfivehuang@gmail.com>
 */

namespace fk\queue;

use fk\queue\commands\Command;
use fk\queue\commands\CommandInterface;
use fk\queue\engines\EngineInterface;

class Connection
{

    /**
     * @var int Second(s) the before the next job start
     */
    public $intervalSeconds = 10;

    /**
     * @var string|array|engines\EngineInterface Engine used to store the queue
     */
    public $engine = 'fk\queue\engines\Redis';

    /**
     * @var string Filename, full path to place the log
     */
    public $logPath = '';

    /**
     * Queue in
     * @param string|array|Command $cmd
     * ```php
     *
     *  $cmd = 'mv app.log app.log1';
     *
     *  // Batch apply system command
     *  $cmd = [
     *      'cd /var/www/html',
     *      'git reset --hard',
     *      'git pull origin master'
     *  ]
     *
     *  // Apply yii command, a.k.a `php yii match/clean user tag`
     *  $cmd = new Command(['match/clean', 'user', 'tag']);
     * ```
     * @return bool
     */
    public function in($cmd)
    {
        if ($cmd instanceof CommandInterface) {
            $cmd = $cmd->parse();
        }

        is_array($cmd) && $cmd = implode(' && ', $cmd);

        return $cmd ? $this->getEngine()->in($cmd) : false;
    }

    /**
     * Executes one job
     */
    public function execute()
    {
        $cmd = $this->getEngine()->out();
        echo "running command $cmd\n";
        if ($cmd) {
            exec($cmd, $result, $code);
            if ($code !== 0) {
                $message = $this->getMessage($code) ?: 'No message';
                $this->log("Error occurs when running queue, extra information: \n" . print_r([
                        'command' => $cmd,
                        'code' => $code,
                        'message' => $message
                    ], true));
            }
        }
        echo "done!\n";
    }

    /**
     * Log on critical point,
     * this will be applied only when
     * [[logPath]] is set or [[engine]] class has method [[log]] (e.g. Redis::log)
     * @see logPath
     * @param mixed $message
     */
    protected function log($message)
    {
        if (method_exists($this->getEngine(), 'log')) {
            $this->getEngine()->log($message);
        } else if ($this->logPath && is_dir(dirname($this->logPath))) {
            is_scalar($message) || $message = print_r($message, true);
            $log = '[' . date('Y-m-d H:i:s') . "] $message\n\n";
            file_put_contents($this->logPath, $log, FILE_APPEND);
        }
    }

    public function getEngine()
    {
        if (false === is_object($this->engine)) {
            $this->engine = $this->createObject($this->engine);
        }
        return $this->engine;
    }

    protected function getMessage($code)
    {
        $messages = [
            1 => 'Command exited with exception',
            2 => 'Misuse of shell built-ins (according to Bash documentation)',
            126 => 'Command invoked cannot execute',
            127 => 'Command not found',
            128 => 'Invalid argument to exit',
            130 => 'Script terminated by Control-C',
            255 => 'Exit status out of range',
        ];

        if (isset($messages[$code])) {
            $message = $messages[$code];
        } else if ($code > 128) {
            $signalMessages = [
                1 => 'hang up',
                2 => 'interrupt',
                3 => 'quit',
                6 => 'abort',
                9 => 'non-catchable, non-ignorable kill',
                14 => 'alarm clock',
                15 => 'software termination signal',
            ];
            $signal = $code - 127;
            $message = 'Fatal terminal signal [' . ($signalMessages[$signal] ?? 'unknown') . '] with code:' . $signal;
        } else {
            $message = 'Unknown message';
        }
        return $message;
    }

    /**
     * @param string|array $class Class to be created, if array given, it must contain a `class` key
     * @return EngineInterface
     * @throws \Exception
     */
    protected function createObject($class)
    {
        if (is_string($class)) {
            // engines\EngineInterface
            if (class_exists($class)) {
                return new $class;
            }
        } else if (is_array($class) && isset($class['class'])) {
            $properties = $class;
            $class = $properties['class'];
            unset($properties['class']);
            return new $class($properties);
        }

        throw new \Exception('Engine for queue\Connection does not exists');
    }

}
