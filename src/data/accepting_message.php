<?php

namespace losthost\OberbotModel\data;

use losthost\DB\DBObject;

class accepting_message extends DBObject {
    
    const METADATA = [
        'ticket_id' => 'BIGINT(20) NOT NULL',
        'message_id' => 'BIGINT(20) NOT NULL',
        'PRIMARY KEY' => 'ticket_id'
    ];
}
