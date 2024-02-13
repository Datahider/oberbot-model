<?php

define('PRODUCTION_DB', true);

use losthost\DB\DB;

require_once '../vendor/autoload.php';

if (PRODUCTION_DB) {
    define('DB_HOST', 'localhost;port=3307');
    define('DB_USER', 'pio-test');
    define('DB_NAME', 'pio-su');
    define('DB_PREF', 'sprt_');
} else {
    define('DB_HOST', 'localhost');
    define('DB_USER', 'test');
    define('DB_NAME', 'test');
    define('DB_PREF', 'obermod_');
}

require_once 'db_pass.php';

DB::connect(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PREF);

