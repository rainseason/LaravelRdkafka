<?php
/**
 * Created by PhpStorm.
 * User: feng
 * Date: 2019/1/30
 * Time: 10:58
 */
return [
    //生产者
    'low_level'=>[
        'producer' => [
            'broker_list'=>'127.0.0.1:9092',
            'topic'=>  'topic',
            'logLevel'=>1, //日志级别  1：LOG_DEBUG,
        ],
        //消费者,一个消费者可以消费多个生产者（集群）的消息
        'consumer'=>[
            'logLevel'=>LOG_DEBUG,                      //日志级别设置
            'broker_list'=>[
                '127.0.0.1:9092',
                '127.0.0.1:9093'
            ],
            'topic'=>  'topic',                        //消息分类
            'begain'=> 1,  //设置消费起始位置  1:RD_KAFKA_OFFSET_BEGINNING; 2:RD_KAFKA_OFFSET_END;  3:RD_KAFKA_OFFSET_STORED  4: rd_kafka_offset_tail()
            'timeout'=>1000,          //消费时间，超市将自动剔除消费者
            'partition' => 'partition ' //分区号
        ]
    ],

];