<?php
/**
 * Created by PhpStorm.
 * User: feng
 * Date: 2019/1/30
 * Time: 17:16
 */
namespace LaravelRdkafka\Rdkafka\Obj;

/**低级客户端使用
 * Class BaseRdkafkaProducer
 * @package LaravelRdkafka\Rdkafka\Src\Obj
 */
class BaseRdkafkaProducer{

    public function __construct($config)
    {
        $this->config = $config['low_level']['producer'];
    }

    /**生产消息
     * @param $message
     */
    public function producer($message){
        $rk = new \RdKafka\Producer();
        $rk->setLogLevel($this->config['logLevel']);
        $rk->addBrokers($this->config['broker_list']);
        $topic = $rk->newTopic($this->config['topic']);
        $topic->produce(RD_KAFKA_PARTITION_UA, 0, $message);
        $rk->poll(0); //不设置消息回调
    }
}