<?php

namespace losthost\OberbotModel\Test\data;
use losthost\OberbotModel\data\topic;
use PHPUnit\Framework\TestCase;

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
}
