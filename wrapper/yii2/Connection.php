<?php

/**
 * @author Felix Huang <yelfivehuang@gmail.com>
 */

namespace fk\queue\wrapper\yii2;

use yii\base\Configurable;

class Connection extends \fk\queue\Connection implements Configurable
{

    public function __construct($config = [])
    {
        if (!empty($config['logPath'])) {
            $this->logPath = \Yii::getAlias($config['logPath']);
        }
    }
}