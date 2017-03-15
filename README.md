
Yii2 extension
----
Extension can work as yii extension too

1. register as a component
```php
# main.php
return [
    'components' => [
        'queue' => [
            'class' => 'fk\queue\wrapper\yii2\Connection',
            'logPath' => '@console/runtime/logs/queue.log',
            'engine' => 'fk\queue\engines\Redis',
        ]
    ]
]
```

2. queue in
```php
Yii::$app->queue->in('ls -l'); // bash> ls -l
Yii::$app->queue->in(new YiiCommand(['migrate'])); // bash> php yii migrate
```
You can write your own `XXCommand` to parse a command, but in the end, a bash command should be returned
If the argument for `in` is empty, then the cmd will be ignored

3. map of console
```php
# console\config\main.php
return [
    'controllerMap' => [
        'queue' => [
            'class' => 'fk\queue\wrapper\yii2\QueueController'
        ]
    ],
]
```

5. queue start
```php
php yii queue/start
```

---

Command
-------
Writing your own command by extends `\fk\queue\commands\Command` and overwrite method `CommandInterface::parse`
```php
<?php

class MyCommand extends \fk\queue\commands\Command
{

    public $command;

    public function parse() {
        // Parse your command with its property `command`
    }
}
```

