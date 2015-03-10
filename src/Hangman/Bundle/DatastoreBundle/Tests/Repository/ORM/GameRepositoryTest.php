<?php

namespace   Hangman\Bundle\DatastoreBundle\Tests\Repository\ORM;

use         Hangman\Bundle\DatastoreBundle\Repository\ORM\GameRepository;
use         Hangman\Bundle\DatastoreBundle\Entity\ORM\Game as GameEntity;

class       GameRepositoryTest 
extends     RepositoryTestBase
{
    function testSave()
    {
        $cMock  = $this->genConnectionMock();
        $emMock = $this->genEntityManagerMock();
        $cmMock = $this->genClassMetadataMock();

        $emMock->expects($this->never())
               ->method('getConnection')
               ->will($this->returnValue($cMock));

        $emMock->expects($this->once())
               ->method('persist');

        $emMock->expects($this->once())
               ->method('flush');

        $repo   = new GameRepository($emMock, $cmMock);
        $game   = new GameEntity();

        $repo->save($game);
    }
} 