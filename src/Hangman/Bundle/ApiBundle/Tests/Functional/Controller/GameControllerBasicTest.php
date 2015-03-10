<?php

namespace   Hangman\Bundle\ApiBundle\Tests\Functional\Controller;

class       GameControllerBasicTest 
extends     GameControllerBaseWebTestCase
{
    protected function setUp()
    {
        $this->loadFixtures(array(
            'Hangman\Bundle\ApiBundle\Tests\DataFixtures\ORM\SingleWordData'
        ));
    }

    public function testCreateAction() 
    {
        $client = $this->createGameClient();

        $client->request('POST', '/games');
        $this->assertGameJsonIsValid($this->getGameJsonFromClient($client));
    }

    public function testStatusAction() 
    {
        $client = $this->createGameClient();    

        // ---
        
        $client->request('POST', '/games');

        $game   = $this->getGameJsonFromClient($client);
        $this->assertGameJsonIsValid($game);

        // ---

        $client->request('GET',  '/games/' . $game->game_id);
        
        $game2  = $this->getGameJsonFromClient($client);
        $this->assertGameJsonIsValid($game2);
    }

    public function testGuessAction()  
    {
        $client = $this->createGameClient();  

        // ---
        
        $client->request('POST', '/games');

        $game   = $this->getGameJsonFromClient($client);
        $this->assertGameJsonIsValid($game);

        // ---
      
        $client->request('POST',  '/games/' . $game->game_id . '/a'); // guessing the letter "a"        
        
        $game2  = $this->getGameJsonFromClient($client);
        $this->assertGameJsonIsValid($game2);
    }
}