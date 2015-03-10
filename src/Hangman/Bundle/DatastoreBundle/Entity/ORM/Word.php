<?php
namespace Hangman\Bundle\DatastoreBundle\Entity\ORM;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Hangman\Bundle\DatastoreBundle\Repository\ORM\WordRepository")
 * @ORM\Table(name="word")
 */
class Word
{
    /**
     * @ORM\Column(name="id", type="integer", options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="word", type="string", nullable=false)
     */
    protected $word;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @param string $word
     */
    public function setWord($word)
    {
        $this->word = trim((string)$word);
    }

    /**
     * @return string
     */
    public function getWord()
    {
        return $this->word;
    }

    /**
     * @ORM\PrePersist 
     * @ORM\PreUpdate
     */
    public function validate()
    {
        if (!is_string($this->word) || !strlen($this->word))
            throw new \Exception('Word must be a string with a length greater than zero'); // FIXME: there is a probabla a more suitable exception that could be thrown here
    }    
}