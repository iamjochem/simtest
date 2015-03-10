<?php

namespace   Hangman\Bundle\ApiBundle\Tests\DataFixtures\ORM;

use         Doctrine\Common\Persistence\ObjectManager;
use         Hangman\Bundle\DatastoreBundle\Entity\ORM\Game as GameEntity;

class       FailedGameData
extends     GameDataBase 
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $game = $this->genGameEntity();
        $chars = str_split($game->getWord());

        foreach (range('a', 'z') as $c) {            
            if (!in_array($c, $chars)
                $game->addCharacterGuessed($c); 

            if ($game->getStatus() === GameEntity::STATUS_FAIL)
                break;
        }

        $manager->persist($game);
        $manager->flush();      
    }
}