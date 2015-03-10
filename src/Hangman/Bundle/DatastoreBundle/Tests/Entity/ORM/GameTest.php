<?php
namespace   Hangman\Bundle\DatastoreBundle\Tests\Entity\ORM;

use         Hangman\Bundle\DatastoreBundle\Entity\ORM\Game as GameEntity;
use         PHPUnit_Framework_TestCase;

class       GameTest 
extends     PHPUnit_Framework_TestCase
{
    public function testDefaultValuesForNewGame()
    {
        $game = new GameEntity();

        $this->assertSame(GameEntity::STATUS_BUSY, $game->getStatus(), 'Default value for `status` property is not "busy"');
        $this->assertSame(11, $game->getTriesLeft(), 'Default value for `tries-left` property is not 11');
        $this->assertSame(array(), $game->getCharactersGuessed(), 'Default value for `characters-guessed` property is not an empty array');
        $this->assertNull($game->getId(), 'Game `id` property should not be set on new games.');
        $this->assertNull($game->getWord(), 'Game `word` property should not be set on new games.');     
    }    

    public function testValidStatusesAllowedOnNewGame()
    {
        $game = new GameEntity();

        foreach (array(
            GameEntity::STATUS_BUSY,
            GameEntity::STATUS_FAIL,       
            GameEntity::STATUS_SUCCESS,
        ) as $s) {            
            $game->setStatus($s);
            $this->assertSame($s, $game->getStatus());
        }
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testIfInvalidStatusIsNotAllowed()
    {
        $game = new GameEntity();
        $game->setStatus('invalid');
    }
} 