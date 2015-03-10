<?php

namespace   Hangman\Bundle\ApiBundle\Tests\DataFixtures\ORM;

use         Doctrine\Common\DataFixtures\AbstractFixture;
use         Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use         Doctrine\Common\Persistence\ObjectManager;

use 	    Hangman\Bundle\DatastoreBundle\Entity\ORM\Word as WordEntity;

class       SingleWordData
extends     AbstractFixture 
implements  OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $word = new WordEntity();
        $word->setWord('scrutinize');

        $manager->persist($word);
        $manager->flush();    	
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }
}