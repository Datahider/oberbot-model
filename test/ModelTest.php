<?php

namespace losthost\OberbotModel\Test;

use PHPUnit\Framework\TestCase;
use losthost\OberbotModel\Model;

class ModelTest extends TestCase {
    
    public function testCreateGetUser() {
        
        $m = new Model();
        
        $user = $m->userByTgId(1000);
        $this->assertFalse($user);
        
        $user = $m->userCreate('Petros', 1000);
        $this->assertEquals('u1', $user->login);
        $this->assertEquals('Petros', $user->name);
        
        $user = $m->userByTgId(1000);
        $this->assertEquals(1, $user->id);
        
        $user = $m->userCreate('Nikos');
        $user = $m->userCreate('Orion');
        
        $this->expectExceptionMessage('User exists');
        $user = $m->userCreate('Ivan', 1000);
    }
}
