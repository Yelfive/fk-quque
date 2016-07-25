
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