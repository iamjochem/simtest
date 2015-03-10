<?php
namespace 	Hangman\Bundle\DatastoreBundle\Tests\Entity\ORM;

use 		Hangman\Bundle\DatastoreBundle\Entity\ORM\Word as WordEntity;
use 		PHPUnit_Framework_TestCase;

class 		WordTest 
extends 	PHPUnit_Framework_TestCase
{
    public function testDefaultValuesForNewWord()
    {
        $word = new WordEntity();

        $this->assertNull($word->getId(),   'Word `id` property should not be set on new words.');
        $this->assertNull($word->getWord(), 'Word `word` property should not be set on new words.');     
    }    

    public function testSettingWordValue()
    {
        $word = new WordEntity();
        $val  = 'something';

        $word->setWord($val);

        $this->assertSame($val, $word->getWord(), 'Could not set word value on Word object.');
    }
}