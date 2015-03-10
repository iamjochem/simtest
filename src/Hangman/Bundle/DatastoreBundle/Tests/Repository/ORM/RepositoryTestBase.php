<?php
namespace       Hangman\Bundle\DatastoreBundle\Tests\Repository\ORM;

use             PHPUnit_Framework_TestCase;

abstract class  RepositoryTestBase 
extends         PHPUnit_Framework_TestCase
{
    protected function genClassMetadataMock()
    {
        return $this->getMockBuilder('Doctrine\ORM\Mapping\ClassMetadata')
                    ->setMethods(array())
                    ->disableOriginalConstructor()
                    ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function genEntityManagerMock()
    {
        return $this->getMockBuilder('Doctrine\ORM\EntityManager')
                    ->setMethods(array('getConnection', 'persist', 'flush'))
                    ->disableOriginalConstructor()
                    ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function genConnectionMock()
    {
        return $this->getMockBuilder('Doctrine\DBAL\Connection')
                    ->setMethods(array('query'))
                    ->disableOriginalConstructor()
                    ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function genStatementMock()
    {
        return  $this->getMockBuilder('Doctrine\DBAL\Driver\Statement')
                     ->setMethods(array())
                     ->disableOriginalConstructor()
                     ->getMock();
    }
} 