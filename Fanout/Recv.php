<?php

namespace Renzhifan\RabbitmqPhp\Fanout;

require __DIR__.'/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

// 创建rabbitmq连接
$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest', 'guest');
// 创建channel
$channel = $connection->channel();

// 声明交换机
$channel->exchange_declare(
    'exchange.fanout', // 交换机名，需要唯一，不能重复
    'fanout', // 交换机类型
    false,
    false, // 是否持久化
    false
);

// 声明队列
list($queue_name, ,) = $channel->queue_declare(
    "", // 队列名，不允许重复，如果为空则自动生成一个唯一ID，这个时候就是一个匿名队列
    false,
    false, // 是否持久化
    true,
    false
);

// 队列绑定指定交换机
$channel->queue_bind(
    $queue_name, // 队列名
    'exchange.fanout' // 交换机名字
);

echo " [*] Waiting for message. To exit press CTRL+C\n";

// 定义消息处理函数（这里使用匿名函数）
$callback = function ($msg) {
    // 消息处理逻辑
    echo ' [x] ', $msg->body, "\n";
};

// 创建消费者
$channel->basic_consume(
    $queue_name, // 队列名，需要消费的队列名
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