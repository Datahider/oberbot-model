<?php

namespace losthost\OberbotModel\builders;

use losthost\OberbotModel\builders\AbstractBuilder;
use losthost\DB\DB;

class MdReport extends AbstractBuilder {
    
    public function build(?array $params = null): array {
        
        $this->checkBuildParams($params);
        
        $sth = DB::prepare($this->getSqlQuery());
        $sth->execute([$params['group'], $params['group']]);
        
        $result = [];
        while ($row = $sth->fetch()) {
            $result[] = [
                'chat_title' => $row['chat_title'],
                'chat_id' => $row['chat_id'],
                'status' => $row['status'],
                'is_task' => $row['is_task'],
                'is_urgent' => $row['is_urgent'],
                'topic_title' => $row['topic_title'],
                'topic_id' => $row['topic_id']
            ];
        }
        
        return $result;
        
    }
    
    protected function getSqlQuery() {
        return <<<FIN
            SELECT 
                IFNULL(chat.title, 'НЕОПРЕДЕЛЕНО') AS chat_title,
                topic.chat_id AS chat_id,
                topic.status AS status,
                topic.is_task AS is_task,
                topic.is_urgent AS is_urgent,
                topic.topic_title AS topic_title,
                topic.topic_id AS topic_id
            FROM 
                [topics] AS topic 
                LEFT JOIN [telle_chats] AS chat ON chat.id = topic.chat_id 
            WHERE 
                topic.status < 100 
                AND topic.chat_id IN (
                        SELECT chat_group.chat_id FROM [chat_groups] AS chat_group WHERE ? = 'all' OR chat_group.chat_group = ?
                    )
            ORDER BY
                is_task, status, is_urgent, chat_title
            FIN;
    }
}
