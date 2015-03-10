<?php
namespace 	Hangman\Bundle\DatastoreBundle\Repository\ORM;

use 	 	Doctrine\ORM\EntityRepository;
use 		Hangman\Bundle\DatastoreBundle\Entity\ORM\Game;

class 		GameRepository 
extends 	EntityRepository
{
    /**
     * @return void
     */
    public function save(Game $game)
    {
        $em = $this->getEntityManager();

        $em->persist($game);
        $em->flush();       
    }
}