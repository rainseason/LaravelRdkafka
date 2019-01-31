<?php
/**
 * Created by PhpStorm.
 * User: feng
 * Date: 2019/1/30
 * Time: 17:16
 */
namespace LaravelRdkafka\Rdkafka\Obj;

/**低级客户端使用
 * Class BaseRdkafka
 * @package LaravelRdkafka\Rdkafka\Src\Obj
 */
class BaseRdkafkaConsumer{


    public function __construct($config)
    {
        $this->config = $config['low_level']['consumer'];
    }

    /**
     * @throws \Exception
     */
    public function consumer(){
        $conf = new \RdKafka\Conf();

// Set the group id. This is required when storing offsets on the broker
        $conf->set('group.id', 'myConsumerGroup');

        $rk = new \RdKafka\Consumer($conf);
        $rk->addBrokers($this->config['broker_list']);

        $topicConf = new RdKafka\TopicConf();
        $topicConf->set('auto.commit.interval.ms', 100);

// Set the offset store method to 'file'
        $topicConf->set('offset.store.method', 'file');
        $topicConf->set('offset.store.path', sys_get_temp_dir());

// Alternatively, set the offset store method to 'broker'
// $topicConf->set('offset.store.method', 'broker');

// Set where to start consuming messages when there is no initial offset in
// offset store or the desired offset is out of range.
// 'smallest': start from the beginning
        $topicConf->set('auto.offset.reset', 'smallest');

        $topic = $rk->newTopic("test", $topicConf);

// Start consuming partition 0
        $topic->consumeStart(0, RD_KAFKA_OFFSET_STORED);

        while (true) {
            $message = $topic->consume(0, 120*10000);
            switch ($message->err) {
                case RD_KAFKA_RESP_ERR_NO_ERROR:
                    var_dump($message);
                    break;
                case RD_KAFKA_RESP_ERR__PARTITION_EOF:
                    echo "No more messages; will wait for more\n";
                    break;
                case RD_KAFKA_RESP_ERR__TIMED_OUT:
                    echo "Timed out\n";
                    break;
                default:
                    throw new \Exception($message->errstr(), $message->err);
                    break;
            }
        }
    }

    /**
     * 单消费者
     */
    public function singleConsumer($callback){
        if( !is_callable( $callback)) {//检测参数是否为合法的可调用结构，测试此函数是否可以被调用
            throw new Exception("callback not callable");
        }
// Disable committing offsets automatically
        $conf = new \RdKafka\Conf();

        $rk = new \RdKafka\Consumer($conf);
        $rk->addBrokers($this->config['broker_list']);

        $topicConf = new RdKafka\TopicConf();
        $topicConf->set('auto.commit.enable', 'false');

        $topic = $rk->newTopic($this->config['topic'], $topicConf);

        $message = $rk->consume(0, 120*1000);

        $result=call_user_func($callback,$message);
        if($result){
            $topic->offsetStore($message->partition, $message->offset);
        }

// After successfully consuming the message, schedule offset store.
// Offset is actually committed after 'auto.commit.interval.ms'
// milliseconds.

    }

    /**
     * 多消费者
     */
    public function multiConsumer(){
        $conf = new \RdKafka\Conf();
        $rk = new \RdKafka\Consumer($conf);
        $rk->addBrokers($this->config['broker_list']);
        $queue = $rk->newQueue();

        $topicConf = new RdKafka\TopicConf();
//        $topicConf->set(...);

        $topic1 = $rk->newTopic("topic1", $topicConf);
        $topic1->consumeQueueStart(0, RD_KAFKA_OFFSET_BEGINNING, $queue);
        $topic1->consumeQueueStart(1, RD_KAFKA_OFFSET_BEGINNING, $queue);

        $topic2 = $rk->newTopic("topic2", $topicConf);
        $topic2->consumeQueueStart(0, RD_KAFKA_OFFSET_BEGINNING, $queue);

// Now, consume from the queue instead of the topics:

        while (true) {
            $message = $queue->consume(120*1000);
            // ...
        }
    }
}