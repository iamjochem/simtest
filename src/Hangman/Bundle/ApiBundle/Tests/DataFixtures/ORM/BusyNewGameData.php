<?php

namespace   Hangman\Bundle\ApiBundle\Tests\DataFixtures\ORM;

use         Doctrine\Common\Persistence\ObjectManager;
use 	    Hangman\Bundle\DatastoreBundle\Entity\ORM\Game as GameEntity;

class       BusyNewGameData
extends     GameDataBase 
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $manager->persist($this->genGameEntity());
        $manager->flush();      
    }
}