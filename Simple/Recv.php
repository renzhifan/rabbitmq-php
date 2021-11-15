<?php

namespace Renzhifan\RabbitmqPhp\Simple;

require __DIR__.'/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

// 创建rabbitmq连接
$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest', 'guest');
// 创建channel
$channel = $connection->channel();

// 声明队列
$channel->queue_declare('hello', false, false, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";

// 定义消息处理函数（这里使用匿名函数）
$callback = function ($msg) {
    // 消息处理逻辑
    echo ' [x] Received ', $msg->body, "\n";
};

// 创建消费者
$channel->basic_consume(
    'hello', // 队列名，需要消费的队列名
    '', // 消费者名，忽略，则自动生成一个唯一ID
    false,
    true, // 是否自动提交消息，即自动告诉rabbitmq消息已经处理成功。
    false,
    false,
    $callback // 消息处理函数
);

// 如果信道没有关闭，则一直阻塞进程，避免进程退出
while ($channel->is_open()) {
    $channel->wait();
}

// 释放资源
$channel->close();
$connection->close();