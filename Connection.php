<?php

/**
 * @author Felix Huang <yelfivehuang@gmail.com>
 */

namespace fk\queue;

use fk\queue\commands\Command;
use fk\queue\engines\EngineInterface;

class Connection
{
    public $engine = 'fk\queue\engines\Redis';

//    public $

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
        if ($cmd instanceof Command) {
            $cmd = $cmd->parse();
        }

        if (is_string($cmd)) {
            $cmd = [$cmd];
        }

        return $this->getEngine()->in($cmd);
    }

    protected function getEngine()
    {
        if (false === is_object($this->engine)) {
            $this->engine = $this->createObject($this->engine);
        }
        return $this->engine;
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

    public function autoload()
    {
        // This is here for projects without there auto load function
    }

    public function shift()
    {

    }
}
