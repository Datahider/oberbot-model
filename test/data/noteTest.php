<?php

namespace losthost\OberbotModel\Test\data;
use losthost\OberbotModel\data\note;
use PHPUnit\Framework\TestCase;

class noteTest extends TestCase {

    // read tests
    public function testReadNotes() : void {
    
        if (!PRODUCTION_DB) {
            $this->assertTrue(true);
            return;
        }
        
        $note = new note(['uuid' => '06ab21cb-15c8-438d-b96c-e8c6751f489f']);
        $this->assertEquals('Пароль', $note->note);
    }
}
