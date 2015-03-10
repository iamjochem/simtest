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

        $resp   = $client->getResponse();

        $this->assertResponseIsJSON($resp);
        $this->assertGameJsonIsValid($this->getGameJsonFromResponse($resp));
    }

    public function testStatusAction() 
    {
        $client = $this->createGameClient();    

        // ---
        
        $client->request('POST', '/games');

        $resp   = $client->getResponse();
        $game   = $this->getGameJsonFromResponse($resp);

        $this->assertResponseIsJSON($resp);
        $this->assertGameJsonIsValid($game);

        // ---

        $client->request('GET',  '/games/' . $game->game_id);
        
        $resp   = $client->getResponse();
        $game2  = $this->getGameJsonFromResponse($resp);
        
        $this->assertResponseIsJSON($resp);        
        $this->assertGameJsonIsValid($game2);
        $this->assertSame($game->game_id, $game2->game_id, 'Game id ichanged across requests.');
    }

    public function testGuessAction()  
    {
        $client = $this->createGameClient();  

        // ---
        
        $client->request('POST', '/games');

        $resp   = $client->getResponse();
        $game   = $this->getGameJsonFromResponse($resp);

        $this->assertResponseIsJSON($resp);
        $this->assertGameJsonIsValid($game);

        // ---
      
        $client->request('POST',  '/games/' . $game->game_id . '/a'); // guessing the letter "a"        
        
        $resp   = $client->getResponse();
        $game2  = $this->getGameJsonFromResponse($resp);

        $this->assertResponseIsJSON($resp);
        $this->assertGameJsonIsValid($game2);
        $this->assertSame($game->game_id, $game2->game_id, 'Game id ichanged across requests.');
    }
}