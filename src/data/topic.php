<?php
namespace losthost\OberbotModel\data;

use losthost\DB\DB;
use losthost\DB\DBObject;

class topic extends DBObject {

    const STATUS_ANY = -1;
    const STATUS_NEW = 0;
    const STATUS_IN_PROGRESS = 1;
    const STATUS_CLOSED = 111;
    
    const METADATA = [
        'id' => 'BIGINT(20) NOT NULL AUTO_INCREMENT',
        'chat_id' => 'BIGINT(20) NOT NULL',
        'topic_id' => 'BIGINT(20) NOT NULL',
        'topic_title' => 'VARCHAR(128) NOT NULL',
        'last_activity' => 'INT(11) NOT NULL DEFAULT 0',
        'last_admin_activity' => 'BIGINT(20) NOT NULL DEFAULT 0',
        'status' => 'TINYINT(4) NOT NULL DEFAULT 0',
        'is_urgent' => 'TINYINT(1) NOT NULL DEFAULT 0',
        'is_task' => 'TINYINT(1) NOT NULL DEFAULT 0',
        'wait_for' => 'BIGINT(20) NULL COMMENT "Ожидание выполнения задачи"',
        'wait_till' => 'VARCHAR(30) NULL COMMENT "Ожидание даты/времени"',
        'PRIMARY KEY' => 'id',
        'UNIQUE INDEX chat_id' => ['chat_id', 'topic_id']
    ];
    
    public static function tableName() {
        return DB::$prefix. 'topics';
    }
    
    static public function newFromTg(int $chat_id, int $topic_id, string $title) {
        
        $new = new topic(['chat_id' => $chat_id, 'topic_id' => $topic_id, 'topic_title' => $title]);
        $new->write();
        $new->topic_title = "$title - #$new->id";
        $new->write();
        
        return $new;
    }
}
