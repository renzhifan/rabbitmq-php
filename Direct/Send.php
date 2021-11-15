<?php

namespace Renzhifan\RabbitmqPhp\Direct;

require __DIR__.'/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

// 创建连接
$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest', 'guest');
// 创建Channel
$channel = $connection->channel();

// 声明交换机
$channel->exchange_declare(
    'exchange.direct', // 交换机名，需要唯一，不能重复
    'direct', // 交换机类型
    false,
    false, // 是否持久化
    false
);


// 消息对象，参数是消息内容
$msg = new AMQPMessage("hello exchange_direct");

// 注意第三个参数，路由参数
$channel->basic_publish(
    $msg, // 消息对象
    'exchange.direct', // 交换机名字
    "hello" // 路由参数，可以根据需求，任意定义。
);

echo ' [x] Sent ', $msg->getBody(), "\n";

// 释放资源
$channel->close();
$connection->close();