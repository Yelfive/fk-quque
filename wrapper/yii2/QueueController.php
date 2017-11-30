<?php

/**
 * @author Felix Huang <yelfivehuang@gmail.com>
 */

namespace fk\queue\wrapper\yii2;

use fk\daemon\Daemon;
use yii\console\Controller;

class QueueController extends Controller
{

    public $interactive = false;

    /**
     * @var bool Run queue at daemon
     */
    public $daemon = false;

    /**
     * @var string The number of the daemon to create
     */
    public $concurrency = 1;

    /**
     * @var int Timeout for a job, in seconds
     */
    public $timeout = 600;

    /**
     * Path for the binary timeout killer
     * @var string
     */
    public $killer = 'bin/job-killer.php';

    /**
     * Starts running the queue
     * @param int $logNil
     */
    public function actionStart($logNil = 0)
    {
        $queue = \Yii::$app->queue;
        while (true) {
            $queue->execute($logNil);
            sleep($queue->intervalSeconds);
        }
    }

    public function optionAliases()
    {
        return ['d' => 'daemon', 'c' => 'concurrency'];
    }

    public function options($actionID)
    {
        return array_merge(parent::options($actionID), [
            'daemon',
//            'concurrency'
        ]);
    }

    /**
     * Stops the queue from running
     */
    public function actionStop()
    {
        $this->stopKiller();
        Daemon::kill('default');
    }

    protected function stopKiller()
    {
        exec("kill `ps -A -o pid,command | grep -v grep | grep -E '$this->killer' | awk '{print $1}'`");
    }

    public function actionStartBeanstalk()
    {
        (new Daemon((int)$this->concurrency, (bool)$this->daemon))
            ->guard([
                \Yii::$app->queue, 'executeTillSuccess'
            ]);

        // Register killer at the background
        exec('php ' . dirname(dirname(__DIR__)) . "/$this->killer $this->timeout> /dev/null &");
    }

}