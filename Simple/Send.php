<?php

namespace Renzhifan\RabbitmqPhp\Simple;

require __DIR__.'/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

// 创建连接ls
$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest', 'guest');
// 创建channel
$channel = $connection->channel();
// 声明队列
$channel->queue_declare('hello', false, false, false, false);

// 定义消息对象
$msg = new AMQPMessage('Hello World!');
// 发送消息
$channel->basic_publish($msg, '', 'hello');

echo " [x] Sent 'Hello World!'\n";

$channel->close();
$connection->close();