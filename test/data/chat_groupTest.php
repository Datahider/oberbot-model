<?php

namespace losthost\OberbotModel\Test\data;
use losthost\OberbotModel\data\chat_group;
use losthost\DB\DBList;

use PHPUnit\Framework\TestCase;

class chat_groupTest extends TestCase {

    // read tests
    public function testReadGroups() : void {
    
        if (!PRODUCTION_DB) {
            $this->assertTrue(true);
            return;
        }
        
        chat_group::initDataStructure();
        $list = new DBList(chat_group::class, ['chat_group' => 'work']);
        
        $this->assertGreaterThan(10, count($list->asArray()));
    }
}
