<?php

namespace losthost\OberbotModel\Test\data;
use losthost\OberbotModel\data\topic_user;
use losthost\DB\DBList;

use PHPUnit\Framework\TestCase;

class topic_userTest extends TestCase {

    // read tests
    public function testReadTopicUsers() : void {
    
        if (!PRODUCTION_DB) {
            $this->assertTrue(true);
            return;
        }
        
        topic_user::initDataStructure();
        $test = new topic_user(['id' => 3]);
        
        $this->assertEquals([19011, 5477605322], [$test->topic_number, $test->user_id]);
    }
}
