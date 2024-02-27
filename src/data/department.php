<?php

namespace losthost\OberbotModel\data;

use losthost\DB\DB;
use losthost\DB\DBObject;

class department extends DBObject {

    const METADATA = [
        'id' => 'BIGINT(20) NOT NULL AUTO_INCREMENT',
        'customer' => 'BIGINT(20)',
        'name' => 'VARCHAR(128) NOT NULL',
        'tg_chat_id' => 'BIGINT(20)',
        'PRIMARY KEY' => 'id'
    ];
  
}
