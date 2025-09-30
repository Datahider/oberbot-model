<?php

namespace losthost\OberbotModel\Test\data;
use losthost\OberbotModel\data\topic;
use PHPUnit\Framework\TestCase;
use losthost\OberbotModel\data\user;
use losthost\OberbotModel\Model;
use losthost\timetracker\Timer;


class topicTest extends TestCase {

    // read tests
    public function testReadTopics() : void {
    
        if (!PRODUCTION_DB) {
            $this->assertTrue(true);
            return;
        }
        
        $topic = new topic(['id' => 21250]);
        $this->assertEquals('Имя для разработчика - #21250', $topic->topic_title);
    }
    
    public function testGetCustomersAndPerformers() {
        
        if (!PRODUCTION_DB) {
            $this->assertTrue(true);
            return;
        }
        
        $topic = new topic(['id' => 21250]);
        $customers = $topic->getCustomers();
        $performers = $topic->getPerformers();
        
        $this->assertEquals('1', $customers[0]->id);
        $this->assertEquals('1', $performers[0]->id);
    }
    
    // write tests
    public function testCreateTopicAndStartStopTimer() : void {
        
        if (PRODUCTION_DB) {
            $this->assertTrue(true);
        }
        
        $topic1 = topic::newFromTg(-800, 123, 'Тестовая заявка');
        $topic2 = topic::newFromTg(-900, 321, 'Другая заявка');
        
        $topic1->startTimer($user);
        sleep(3);
        $topic2->startTimer($user);
        sleep(2);
        $topic2->stopTimer($user);
        
        $timer = new Timer($user->id);
        
        $this->assertFalse($timer->isStarted());
    }
}
