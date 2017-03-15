<?php

/**
 * @author Felix Huang <yelfivehuang@gmail.com>
 */

namespace fk\queue\wrapper\yii2;

use yii\console\Controller;

class QueueController extends Controller
{

    public $interactive = false;

    /**
     * Starts running the queue
     * @internal param array $args
     */
    public function actionStart()
    {
        // TODO: catch error with exit code greater than 0,
        // TODO: it may have something to do with Response, yii
        // TODO: un-catchable ?
        $queue = \Yii::$app->queue;
        while (true) {
            $queue->execute();
            sleep($queue->intervalSeconds);
        }
    }

    public function optionAliases()
    {
        return ['d' => 'daemon'];
    }

    /**
     * Stops the queue from running
     */
    public function actionStop()
    {
        exec('ps aux|prep php yii queue');
    }

}