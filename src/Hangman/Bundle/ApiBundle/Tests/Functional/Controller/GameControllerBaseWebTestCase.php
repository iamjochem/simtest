<?php

namespace   	Hangman\Bundle\ApiBundle\Tests\Functional\Controller;

use             Liip\FunctionalTestBundle\Test\WebTestCase;
use         	Symfony\Component\HttpFoundation\Response;

use             Doctrine\Common\DataFixtures\Purger\ORMPurger;

use         	Hangman\Bundle\DatastoreBundle\Entity\ORM\Game as GameEntity;

abstract class 	GameControllerBaseWebTestCase
extends 	   	WebTestCase
{
	/**
	 * default client options 
	 *
	 * @see self::createGameClient()
	 * @var array
	 */
    static protected $clientOpts   = array();

	/**
	 * default client options 
	 * 
	 * @see self::createGameClient()
	 * @var array
	 */
    static protected $serverParams = array();

    /**
     * truncate every table in the database 
     * 
     * @return void
     */
    protected function purgeDatabase()
    {   
        $doctrine   = $this->getContainer()->get('doctrine');
        $em         = $doctrine->getManager();
        $dbconn     = $em->getConnection();
        $platform   = $dbconn->getDatabasePlatform();
        $purger     = new ORMPurger($em);
        
        // get rid of all the things ...
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_TRUNCATE);
        $purger->purge();        

        // start count all the new things from 1 ....    
        foreach ($em->getMetadataFactory()->getAllMetadata() as $metadata) 
            if (!$metadata->isMappedSuperclass)
                $dbconn->executeUpdate("ALTER TABLE " . $metadata->getQuotedTableName($platform) . " AUTO_INCREMENT=1;");
    }

    /**
     * perform basic assertions on the given Response
     * 
     * @param  Response $resp 
     * @return void
     */
    protected function assertResponseIsJSON(Response $resp)
    {
        $this->assertTrue($resp->headers->contains('Content-Type', 'application/json'), 'Response Content-Type is not "application/json"');
    }

    /**
     * extract the decoded JSON data from the given client instance.
     * 
     * @param   Response $resp 
     * @return  mixed               
     */
    protected static function getGameJsonFromResponse(Response $resp)
    {        
        $raw  = $resp->getContent();        
        $json = is_string($raw) && strlen($raw) ? json_decode($raw) : null;

        return $json;
    }

    /**
     * perform assertions on [decoded] game JSON data.
     * 
     * N.B. the assumption is that the data is represented in PHP as an object,
     *      technically it could be an assoc. array - it would be better if there 
     *      was a more generic manner in which to extract/check the properties of
     *      the data (i.e. without assuming the exact data-type of the decoded JSON)
     * 
     * @param  stdObject $json 
     * @return void
     */
    protected function assertGameJsonIsValid($json)
    {
        $this->assertTrue(is_object($json), 'Game JSON: data is not an object');

        $this->assertObjectHasAttribute('game_id',      $json);
        $this->assertObjectHasAttribute('tries_left',   $json);
        $this->assertObjectHasAttribute('status',       $json);
        $this->assertObjectHasAttribute('word',         $json);
        
        $this->assertAttributeNotEmpty('game_id',       $json);
        $this->assertAttributeNotEmpty('tries_left',    $json);
        $this->assertAttributeNotEmpty('status',        $json);
        $this->assertAttributeNotEmpty('word',          $json);

        $this->assertTrue(is_int($json->game_id), 'Game JSON: `game_id` is not an int');
        $this->assertAttributeGreaterThan(0, 'game_id', $json);

        $this->assertTrue(is_int($json->tries_left), 'Game JSON: `tries_left` is not an int');
        $this->assertAttributeGreaterThan(-1, 'tries_left', $json);

        $this->assertTrue(is_string($json->status), 'Game JSON: `status` is not a string');
        $this->assertGreaterThan(0, strlen($json->status));
        $this->assertContains($json->status, self::getValidGameStatusValues());

        $this->assertTrue(is_string($json->word), 'Game JSON: `word` is not a string');
        $this->assertGreaterThan(0, strlen($json->word)); // note: we removed words shorter than 4 chars from the DB, our test should reflect this?
    }

    /**
     * returns an array of valid values for `GameEntity->status` property
     * 
     * @return array[int>string]
     */
    protected static function getValidGameStatusValues()
    {
        return array(
            GameEntity::STATUS_BUSY,
            GameEntity::STATUS_FAIL,       
            GameEntity::STATUS_SUCCESS,
        );
    }

    /**
     * wrapper for self::createClient(), simplifies reuse of client opts & server params
     * 
     * @param  array  $opts   
     * @param  array  $params 
     * @return Client
     */
    protected static function createGameClient($opts = array(), $params = array())
    {
        return static::createClient(
            array_merge(static::$clientOpts, $opts),
            array_merge(static::$serverParams, $params)
        ); 
    }
}