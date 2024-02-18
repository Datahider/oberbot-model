<?php

namespace losthost\OberbotModel;

use losthost\OberbotModel\data\chat_group;
use losthost\OberbotModel\data\note;
use losthost\OberbotModel\data\topic;
use losthost\OberbotModel\data\topic_admin;
use losthost\OberbotModel\data\topic_user;
use losthost\OberbotModel\data\user;

use losthost\DB\DBList;

class Model {

    public function __construct() {
        chat_group::initDataStructure();
        note::initDataStructure();
        topic::initDataStructure();
        topic_admin::initDataStructure();
        topic_user::initDataStructure();
        user::initDataStructure();
    }
    
    public function userByTgId(int $tg_id) {
        $user = new user(['tg_id' => $tg_id], true);
        
        if ($user->isNew()) {
            return false;
        }
        
        return $user;
    }
    public function userCreate(?string $name=null, ?int $tg_id=null, ?string $login=null, ?string $password=null) {
        
        $existing = new DBList(user::class, ':login IS NOT NULL AND login = :login OR :tg_id IS NOT NULL AND tg_id = :tg_id', ['login' => $login, 'tg_id' => $tg_id]);
        if (count($existing->asArray())) {
            throw new \Exception('User exists');
        }
        
        $user = new user(['tg_id' => $tg_id, 'name' => $name, 'login' => $login], true);
        $user->write();
        
        if (!$user->login) {
            $user->login = 'u'. $user->id;
        }
        
        if ($password) {
            $user->password_hash = sha1($password);
        }
        
        if ($user->isModified()) {
            $user->write();
        }
        
        return $user;
    }
    
}
