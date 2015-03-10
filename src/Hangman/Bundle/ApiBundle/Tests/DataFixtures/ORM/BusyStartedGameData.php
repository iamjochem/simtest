<?php

namespace   Hangman\Bundle\ApiBundle\Tests\DataFixtures\ORM;

use         Doctrine\Common\Persistence\ObjectManager;
use 	    Hangman\Bundle\DatastoreBundle\Entity\ORM\Game as GameEntity;

class       BusyStartedGameData
extends     GameDataBase 
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $game = $this->genGameEntity();

        foreach (range('a', 'c') as $c) // make 3 guesses
            $game->addCharacterGuessed($c);

        $manager->persist($game);
        $manager->flush();      
    }
}