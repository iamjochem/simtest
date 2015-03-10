<?php

namespace   Hangman\Bundle\ApiBundle\Tests\DataFixtures\ORM;

use         Doctrine\Common\DataFixtures\AbstractFixture;
use         Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use         Doctrine\Common\Persistence\ObjectManager;

use 	    Hangman\Bundle\DatastoreBundle\Entity\ORM\Game as GameEntity;

abstract class  GameDataBase
extends         AbstractFixture 
implements      OrderedFixtureInterface
{
    /**
     * default word to initialize testdata Game entities with.
     * 
     * @const   string
     */
    const GAME_WORD = 'scrutinize';

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }

    /**
     * @return GameEntity
     */
    protected function genGameEntity()
    {
        $game = new GameEntity();
        $game->setWord(static::GAME_WORD);
    }
}