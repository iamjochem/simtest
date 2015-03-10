<?php

namespace   Hangman\Bundle\ApiBundle\Tests\DataFixtures\ORM;

use         Doctrine\Common\Persistence\ObjectManager;
use 	    Hangman\Bundle\DatastoreBundle\Entity\ORM\Game as GameEntity;

class       SuccessfulGameData
extends     GameDataBase 
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $game  = $this->genGameEntity();
        $chars = str_split($game->getWord());
        $len   = count($chars) + 5; // implies 5 failed guesses

        foreach (range('a', 'z') as $c) {            
            if (!in_array($c, $chars)
                $chars[] = $c;

            if (count($chars) === $len)
                break;
        }

        foreach (array_reverse($chars) as $c)
            $game->addCharacterGuessed($c);

        $manager->persist($game);
        $manager->flush();      
    }
}