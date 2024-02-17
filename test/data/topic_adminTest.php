<?php

namespace losthost\OberbotModel\Test\data;
use losthost\OberbotModel\data\topic_admin;
use losthost\DB\DBList;

use PHPUnit\Framework\TestCase;

class topic_adminTest extends TestCase {

    // read tests
    public function testReadTopicAdmins() : void {
    
        if (!PRODUCTION_DB) {
            $this->assertTrue(true);
            return;
        }
        
        topic_admin::initDataStructure();
        $test = new topic_admin(['id' => 16]);
        
        $this->assertEquals([20000, 203645978], [$test->topic_number, $test->user_id]);
    }
}
