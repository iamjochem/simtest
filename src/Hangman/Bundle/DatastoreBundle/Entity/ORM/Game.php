<?php
namespace Hangman\Bundle\DatastoreBundle\Entity\ORM;

use InvalidArgumentException;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Hangman\Bundle\DatastoreBundle\Repository\ORM\GameRepository")
 * @ORM\Table(name="game")
 */
class Game
{
    /**
     * valid values for the `status` property
     */
    const STATUS_BUSY       = 'busy';
    const STATUS_FAIL       = 'fail';
    const STATUS_SUCCESS    = 'success';

    /**
     * character used to replace unguessed letters in the word when it 
     * is output as part of the game data (e.g. a JSON response from the game API). 
     *
     * @const string
     */
    const REPLACEMENT_CHAR  = '.';

    /**
     * [default] max. number of incorrect guesses that are allowed
     */
    const MAX_TRIES         = 11;

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
     * @ORM\Column(name="tries_left", type="integer", nullable=false, options={"unsigned":true, "default":11})
     */
    protected $tries_left   = self::MAX_TRIES;

    /**
     * @ORM\Column(name="status", type="string", nullable=false)
     */
    protected $status       = self::STATUS_BUSY;

    /**
     * @ORM\Column(name="characters_guessed", type="json_array")
     */
    protected $charactersGuessed = array();

    /**
     * @param  string   $char
     * @return boolean              - whether the given char was added or not. 
     */
    public function addCharacterGuessed($char)
    {
        if (!is_string($char) || strlen($char) !== 1)
            return false; // throw?

        if ($this->tries_left === 0)
            return false; // throw?

        if ($this->status !== self::STATUS_BUSY)
            return false; // throw?

        $this->charactersGuessed[] = $char;

        if (strpos($this->word, $char) === false)
            $this->tries_left -= 1;

        $this->calculateStatus();

        return true;
    }

    /**
     * @return mixed
     */
    public function getCharactersGuessed()
    {
        return $this->charactersGuessed;
    }

    /**
     * @param mixed $charactersGuessed
     */
    public function setCharactersGuessed($charactersGuessed)
    {
        $this->charactersGuessed = $charactersGuessed;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        if (!in_array($status, array(self::STATUS_BUSY, self::STATUS_FAIL, self::STATUS_SUCCESS)))
            throw new InvalidArgumentException("Invalid status");

        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param integer $tries_left
     */
    public function setTriesLeft($tries_left)
    {
        $this->tries_left = abs((int)$tries_left);
    }

    /**
     * @return integer
     */
    public function getTriesLeft()
    {
        return $this->tries_left;
    }

    /**
     * @param string $word
     */
    public function setWord($word)
    {
        $this->word = $word;
    }

    /**
     * @return string
     */
    public function getWord()
    {
        return $this->word;
    }

    /**
     * this function returns a string representation of this game's word where
     * each unguessed character has been replaced with the self::REPLACEMENT_CHAR character
     * 
     * @return string
     */
    public function getGuessedWord()
    {
        $guesses = $this->charactersGuessed;
        $guessed = function($c) use ($guesses) { return in_array($c, $guesses) ? $c : self::REPLACEMENT_CHAR; };

        return join(array_map($guessed, str_split($this->word)));
    }

    /**
     * @return void
     */
    public function calculateStatus()
    {
        switch ($this->status) {
            case self::STATUS_FAIL   :
            case self::STATUS_SUCCESS:
                return;
        }

        if ($this->word === $this->getGuessedWord())
            $this->setStatus(self::STATUS_SUCCESS);
        else if ($this->tries_left === 0)
            $this->setStatus(self::STATUS_FAIL);
    }

    /**
     * @ORM\PrePersist 
     * @ORM\PreUpdate
     *
     * TODO: Does this need to be refactored to use symfony's Validator service?
     */
    public function notCompleted($throw = true)
    {
        $id = $this->getId();

        if (!$id)
            return true;

        if ($this->status === self::STATUS_BUSY)
            return true;

        // FIXME: I'm sure there is a more specific exception that can be thrown here, also 
        if ($throw)
            throw new \Exception("Game #{$id} has already been finished."); 

        return false;
    }

    /**
     * @ORM\PrePersist 
     * @ORM\PreUpdate
     *
     * TODO: Does this need to be refactored to use symfony's Validator service?
     */
    public function validate()
    {
        // "tries left" must be int
        // "word" must be a string with length
        // "status" must be a recognized value 
    }    
}