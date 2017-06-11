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
        $this->setConfig($config);
        if (!empty($this->logPath)) {
            $this->logPath = \Yii::getAlias($this->logPath);
        }
    }

    protected function setConfig($config)
    {
        if (is_array($config)) {
            foreach ($config as $k => $v) {
                $this->$k = $v;
            }
        }
    }
}