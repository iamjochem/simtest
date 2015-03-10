<?php

namespace   Hangman\Bundle\DatastoreBundle\DataFixtures\ORM;

use         Doctrine\Common\DataFixtures\AbstractFixture;
use         Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use         Doctrine\Common\Persistence\ObjectManager;

use         Goodby\CSV\Import\Standard\Lexer;
use         Goodby\CSV\Import\Standard\Interpreter;
use         Goodby\CSV\Import\Standard\LexerConfig;

class       LoadWordData 
extends     AbstractFixture 
implements  OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $lexer  = new Lexer(new LexerConfig());
        $dbconn = $manager->getConnection();

        $minlen = 4; // words with less that 4 chars are too easy to guess :)
        $words  = array();

        $interpreter = new Interpreter();
        $interpreter->addObserver(function(array $row) use (&$words, $minlen, $dbconn) {
            if (strlen($row[0]) >= $minlen)
                $words[] = '('.$dbconn->quote($row[0], \PDO::PARAM_STR).')';
        });

        $lexer->parse(__DIR__.'/../../Resources/files/words.english', $interpreter);

        if (count($words))
            $dbconn->exec('INSERT INTO word (word) VALUES '.implode(',',$words));
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }
}