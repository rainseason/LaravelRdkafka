<?php
/**
 * Created by PhpStorm.
 * User: feng
 * Date: 2019/1/30
 * Time: 10:59
 */
namespace LaravelRdkafka\Rdkafka;


use LaravelRdkafka\Rdkafka\Src\Obj\BaseRdkafkaConsumer;
use LaravelRdkafka\Rdkafka\Src\Obj\BaseRdkafkaProducer;

class Rdkafka
{
    /**
     * @var Repository
     */
    protected $config;

    /**
     * @var Catch the errors
     */
    public $error;

    /**
     * Toastr constructor.
     * @param SessionManager $session
     * @param Repository $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    protected function initialKafkaConf(){
        $conf = new \RdKafka\Conf();
        //配置消费者
//        $conf->set('group.id', 'myConsumerGroup');       //属性配置
//        $conf->setDefaultTopicConf (\RdKafka\TopicConf  $topic_conf );  //默认topic配置
        /***************************************************************/
        /**=====================【setDrMsgCb】=========================
         * 1.生产者生产消息成功回调
         * 2.kafka遭遇永久失败回调
         * 3.尝试记录错误次数频繁
         * 4.使用的同时要使用RdKafka::poll() 来处理队列回调
         * 5.@example
        $conf->setDrMsgCb(function ($kafka, $message) {
        if ($message->err) {
        // message permanently failed to be delivered
        } else {
        // message successfully delivered
        }
        });
         */
//        $conf->setDrMsgCb ( callable $callback  );  //消息回调处理，
        /***************************************************************/
        /***************************************************************/
        /**=====================【setErrorCb】=========================
         * 1.用来处理被kafka标记为严重错误的消息
         * 2.@example
        $conf->setErrorCb(function ($kafka, $err, $reason) {
        printf("Kafka error: %s (reason: %s)\n", rd_kafka_err2str($err), $reason);
        });
         */
//        $conf->setErrorCb  ( callable $callback  );  //失败消息回调，
        /***************************************************************/
        /***************************************************************/
         /**=====================【setRebalanceCb 】=========================
          * 1.设置重新平衡回调以用于协调的消费者组平衡
          */
//        $conf->setRebalanceCb(function (RdKafka\KafkaConsumer $kafka, $err, array $partitions = null) {
//            switch ($err) {
//                case RD_KAFKA_RESP_ERR__ASSIGN_PARTITIONS:
//                    // application may load offets from arbitrary external
//                    // storage here and update partitions
//                    $kafka->assign($partitions);
//                    break;
//
//                case RD_KAFKA_RESP_ERR__REVOKE_PARTITIONS:
//                    if ($manual_commits) {
//                        // Optional explicit manual commit
//                        $kafka->commit($partitions);
//                    }
//                    $kafka->assign(NULL);
//                    break;
//
//                default:
//                    handle_unlikely_error($err);
//                    $kafka->assign(NULL); // sync state
//                    break;
//            }
//        }
        /***************************************************************/
    }

    public function lowProducer()
    {
       return new BaseRdkafkaProducer($this->config);
    }

    public function lowConsumer()
    {
        return new BaseRdkafkaConsumer($this->config);
    }
}