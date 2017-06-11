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
     * Starts running the queue
     * @param int $logNil
     */
    public function actionStart($logNil = 0)
    {
        // TODO: catch error with exit code greater than 0,
        // TODO: it may have something to do with Response, yii
        // TODO: un-catchable ?
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
//        exec('ps aux|prep php yii queue');
        Daemon::kill('default');
    }

    public function actionStartBeanstalk()
    {

        (new Daemon((int)$this->concurrency, (bool)$this->daemon))
            ->guard([
                \Yii::$app->queue, 'executeTillSuccess'
            ]);
    }

}