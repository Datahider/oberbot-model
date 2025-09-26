<?php
namespace losthost\OberbotModel\data;

use losthost\DB\DB;
use losthost\DB\DBObject;
use losthost\DB\DBList;
use losthost\OberbotModel\data\user;
use losthost\OberbotModel\data\topic_user;
use losthost\OberbotModel\data\topic_admin;
use losthost\timetracker\Timer;

class topic extends DBObject {

    const STATUS_ANY = -1;
    const STATUS_NEW = 0;
    const STATUS_IN_PROGRESS = 1;
    const STATUS_PENDING = 101;
    const STATUS_CLOSED = 111;
    
    const TYPE_REGULAR_TASK = 1;
    const TYPE_PRIORITY_TASK = 2;
    const TYPE_MALFUNCTION = 3;
    const TYPE_SCHEDULED_CONSULT = 4;
    const TYPE_URGENT_CONSULT = 5;
    const TYPE_MALFUNCTION_MULTIUSER = 6;
    const TYPE_MALFUNCTION_FREE = 7;
    const TYPE_BOT_SUPPORT = 8;
    const TYPE_PRIVATE_SUPPORT = 9;
    
    const METADATA = [
        'id' => 'BIGINT(20) NOT NULL AUTO_INCREMENT',
        'chat_id' => 'BIGINT(20) NOT NULL',
        'topic_id' => 'BIGINT(20) NOT NULL',
        'created' => 'DATETIME',
        'topic_title' => 'VARCHAR(128) NOT NULL',
        'ticket_creator' => 'BIGINT(20) NULL',
        'last_activity' => 'BIGINT(20) NOT NULL DEFAULT 0',
        'last_admin_activity' => 'BIGINT(20) NOT NULL DEFAULT 0',
        'status' => 'TINYINT(4) NOT NULL DEFAULT 0',
        'type' => 'INT(11)',
        'score' => 'TINYINT(4)',
        'is_urgent' => 'TINYINT(1) NOT NULL DEFAULT 0',
        'is_task' => 'TINYINT(1) NOT NULL DEFAULT 0',
        'user_priority' => 'TINYINT(4) NOT NULL DEFAULT 3',
        'wait_for' => 'BIGINT(20) NULL COMMENT "Ожидание выполнения задачи"',
        'wait_till' => 'VARCHAR(30) NULL COMMENT "Ожидание даты/времени"',
        'PRIMARY KEY' => 'id',
        'UNIQUE INDEX chat_id' => ['chat_id', 'topic_id']
    ];
    
    public static function tableName() {
        return DB::$prefix. 'topics';
    }
    
    public function write($comment = '', $data = null) {
        
        if ($this->type == null) {
            if (!$this->is_task) {
                $this->type = topic::TYPE_MALFUNCTION;
            } elseif (!$this->is_urgent) {
                $this->type = topic::TYPE_REGULAR_TASK;
            } else {
                $this->type = topic::TYPE_PRIORITY_TASK;
            }
        }
        
        if (!$this->user_priority) {
            $this->user_priority = 3;
        }
        parent::write($comment, $data);
    }
    
    public function addIdToTitle() {

        if (preg_match("/\#$this->id$/", $this->topic_title)) {
            return; // уже добавлено
        }
        
        $text_id = " #$this->id";
        $max_len = 128 - strlen($text_id);
        $this->topic_title =  substr($this->topic_title, 0, $max_len). $text_id;
        
    }
    
    static public function newFromTg(int $chat_id, int $topic_id, string $title) {
        
        $new = new topic(['chat_id' => $chat_id, 'topic_id' => $topic_id, 'topic_title' => $title], true);
        if ($new->isNew()) {
            DB::beginTransaction();
            $new->write();
            $new->topic_title = "$title - #$new->id";
            $new->write();
            DB::commit();
        } else {
            throw new \Exception('This topic already exists.');
        }
        return $new;
    }
    
//    public function getCustomers() : array {
//        
//        $users = new DBList(user::class, 'id IN (SELECT user_id FROM [topic_users] WHERE topic_number = ?)', [$this->id]);
//        return $users->asArray();
//    }

    public function getPerformers() : array {
        
        $users = new DBList(user::class, 'id IN (SELECT user_id FROM [topic_admins] WHERE topic_number = ?)', [$this->id]);
        return $users->asArray();
    }
    
    public function addCustomer(user $user) : bool {

        $topic_customer = new topic_user(['topic_number' => $this->id, 'user_id' => $user->id], true);
        if ($topic_customer->isNew()) {
            $topic_customer->write();
            return true;
        } else {
            return false;
        }
    }
    
    public function addPerformer(user $user) : bool {
        
        $topic_performer = new topic_admin(['topic_number' => $this->id, 'user_id' => $user->id], true);
        if ($topic_performer->isNew()) {
            $topic_performer->write();
            return true;
        } else {
            return false;
        }
    }
    
    public function startTimer(user $user) {
        $timer = new Timer($user->id);
        if ($timer->isStarted()) {
            $timer->stop("Stopped by starting timer for ticket #$this->id");
        }
        $timer->start($this->id, $this->chat_id);
    }
    
    public function stopTimer(user $user) {
        $timer = new Timer($user->id);
        $timer->stop();
    }
}
