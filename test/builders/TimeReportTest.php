<?php

namespace losthost\OberbotModel\Test\builders;

use PHPUnit\Framework\TestCase;
use losthost\OberbotModel\builders\TimeReport;

class TimeReportTest extends TestCase {
 
    public function testTimeReport() {
    
        if (!PRODUCTION_DB) {
            $this->assertTrue(true);
            return;
        }
        
        $params = [
            'period_start' => '2024-02-01',
            'period_end' => '2024-03-01',
            'project' => -1001939586105
        ];
        
        $time_report = new TimeReport();
        
        $result = $time_report->build($params);
        
        $this->assertEquals(21283, $result[0]->topic->id);
        $this->assertEquals(16693, $result[1]->total_seconds);
        $this->assertEquals('Полный номер заказа на перемещение - #21252', $result[15]->topic->topic_title);
        
    }
}
