<?php

namespace losthost\OberbotModel\data;

use losthost\DB\DBObject;

class user extends DBObject {
    
    const METADATA = [
        'id' => 'BIGINT(20) NOT NULL AUTO_INCREMENT',
        'tg_id' => 'BIGINT(20)',
        'login' => 'VARCHAR(128)',
        'password_hash' => 'VARCHAR(256)',
        'name' => 'VARCHAR(512)',
        'last_activity' => 'DATETIME',
        'PRIMARY KEY' => 'id',
        'UNIQUE INDEX TG_ID' => 'tg_id',
        'UNIQUE INDEX LOGIN' => 'login'
    ];
}
