<?php

namespace   Hangman\Bundle\ApiBundle\Tests\Functional\Controller;

class       GameControllerExtendedTest 
extends     GameControllerBaseWebTestCase
{   
    protected function setUp()
    {

    }    

    protected function tearDown() 
    {
        $this->purgeDatabase(); // TODO: is it better to replace this line with `$this->loadFixtures(array())` ?
        parent::tearDown();
    }

    function testCannotRetrieveNonexistentGame()
    {
        $client = $this->createGameClient();
        $client->request('GET', '/games/1');
        
        $resp   = $client->getResponse();
        
        $this->assertResponseIsJSON($resp);        
        $this->assertSame($resp->getStatusCode(), 404, 'Retrieving a non-existent game does not generate a 404 HTTP response.');
    }

    function testCannotAddGuessToSuccessfulGame()
    {
        $this->loadFixtures(array(
            'Hangman\Bundle\ApiBundle\Tests\DataFixtures\ORM\SuccessfulGameData'
        ));

        $client = $this->createGameClient();

        $client->request('POST', '/games/1/a');
        
        $resp   = $client->getResponse();
        
        $this->assertResponseIsJSON($resp);        
        $this->assertSame($resp->getStatusCode(), 400, 'Trying to guess a letter on a successful (completed) game does not generate a 400 HTTP response.');
    }

    function testCannotAddGuessToFailedGame()
    {
        $this->loadFixtures(array(
            'Hangman\Bundle\ApiBundle\Tests\DataFixtures\ORM\FailedGameData'
        ));

        $client = $this->createGameClient();

        $client->request('POST', '/games/1/a');
        
        $resp   = $client->getResponse();
        
        $this->assertResponseIsJSON($resp);        
        $this->assertSame($resp->getStatusCode(), 400, 'Trying to guess a letter on a successful (completed) game does not generate a 400 HTTP response.');
    }
}