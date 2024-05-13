<?php

namespace losthost\OberbotModel\Test\builders;

use PHPUnit\Framework\TestCase;
use losthost\OberbotModel\builders\MdReport;

class MdReportTest extends TestCase {
    
    public function testReport() {
        
        if (!PRODUCTION_DB) {
            $this->assertTrue(true);
            return;
        }

        $report = new MdReport();
        $result = $report->build(['group' => 'work']);
        
        $this->assertGreaterThan(0, count($result));
        $this->assertNotNull($result[0]['chat_title']);
        $this->assertNotNull($result[0]['chat_id']);
        $this->assertNotNull($result[0]['status']);
        $this->assertNotNull($result[0]['is_task']);
        $this->assertNotNull($result[0]['is_urgent']);
        $this->assertNotNull($result[0]['topic_title']);
        $this->assertNotNull($result[0]['topic_id']);
        
        print_r($result[0], true);
    }
}
