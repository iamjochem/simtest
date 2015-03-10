<?php

namespace   Hangman\Bundle\ApiBundle\Tests\Functional\Controller;

class       GameControllerExtendedTest 
extends     GameControllerBaseWebTestCase
{
    protected function tearDown() 
    {
        $this->purgeDatabase();
        parent::tearDown();
    }
}