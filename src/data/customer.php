<?php

namespace losthost\OberbotModel\data;

use losthost\DB\DB;
use losthost\DB\DBObject;

class customer extends DBObject {

    const METADATA = [
        'id' => 'BIGINT(20) NOT NULL AUTO_INCREMENT',
        'name' => 'VARCHAR(128) NOT NULL',
        'PRIMARY KEY' => 'id'
    ];

}
