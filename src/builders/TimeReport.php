<?php

namespace losthost\OberbotModel\builders;

use losthost\OberbotModel\builders\AbstractBuilder;
use losthost\DB\DBView;
use losthost\OberbotModel\data\topic;

class TimeReport extends AbstractBuilder {
    
    public function build(?array $params = null): array {
    
        $this->checkBuildParams($params);
        
        $sql = <<<FIN
            SELECT 
                project,
                object,
                topics.topic_title AS topic_title,
                IFNULL(
                    SUM(
                        CASE
                            WHEN events.end_time IS NULL THEN TIMESTAMPDIFF(SECOND, GREATEST(:period_start, events.start_time), :period_end) 
                            ELSE TIMESTAMPDIFF(SECOND, GREATEST(:period_start, events.start_time), LEAST(:period_end, events.end_time)) 
                        END
                    ), 
                    0) AS total_seconds
            FROM 
                [timer_events] AS events
                LEFT JOIN [topics] AS topics ON topics.id = events.object
            WHERE 
                (:project = 'any' OR project = :project)
                AND end_time >= :period_start
                AND start_time <= :period_end
                AND comment <> 'stopped as you trying to start started timer'
            GROUP BY 
                project,
                object,
                topic_title
            ORDER BY total_seconds DESC
            FIN;
        $view = new DBView($sql, $params);
        
        $result = [];
        while ($view->next()) {
            $result[] = (object)[
                'project' => $view->project,
                'topic' => new topic(['id' => $view->object]),
                'total_seconds' => $view->total_seconds
            ];
        }
        
        return $result;
    }
    
    protected function checkBuildParams(?array &$params) {
        parent::checkBuildParams($params);
        
        if (empty($params['project'])) {
            $params['project'] = 'any';
        }
        
        if (empty($params['period_start'])) {
            throw new \Exception('Param period_start is not defined.');
        }
        if (empty($params['period_end'])) {
            throw new \Exception('Param period_end is not defined.');
        }
    }
}
