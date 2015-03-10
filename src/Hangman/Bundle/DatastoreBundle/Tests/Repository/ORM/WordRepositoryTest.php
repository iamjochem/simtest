<?php
namespace   Hangman\Bundle\DatastoreBundle\Tests\Repository\ORM;

use         Hangman\Bundle\DatastoreBundle\Repository\ORM\WordRepository;

class       WordRepositoryTest 
extends     RepositoryTestBase
{
    protected $expectedWord         = 'tested';
    protected $noWordsInDatastore   = false;

    /**
     * @expectedException RuntimeException
     */
    public function testNoWordsInDatastore()
    {
        $this->noWordsInDatastore = true;
        $this->genWordRepository()->getRandomWord();
    }

    public function testRandomWordReturned()
    {
        $this->assertEquals(
            $this->genWordRepository()->getRandomWord(),
            $this->expectedWord,
            'Word does not match the expected result'
        );
    }

    protected function genWordRepository()
    {
        $cMock  = $this->genConnectionMock();
        $stMock = $this->genStatementMock();
        $emMock = $this->genEntityManagerMock();
        $cmMock = $this->genClassMetadataMock();

        $stMock->expects($this->once())
               ->method('fetch')
               ->will($this->returnValue($this->noWordsInDatastore ? false : array('word' => $this->expectedWord)));

        $cMock ->expects($this->once())
               ->method('query')
               ->will($this->returnValue($stMock));

        $emMock->expects($this->once())
               ->method('getConnection')
               ->will($this->returnValue($cMock));

        return new WordRepository($emMock, $cmMock);
    }
} 