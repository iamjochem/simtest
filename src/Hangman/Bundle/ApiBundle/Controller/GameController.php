<?php

namespace Hangman\Bundle\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
	Symfony\Component\HttpFoundation\Response,
	Symfony\Component\HttpFoundation\JsonResponse,
	Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use Hangman\Bundle\DatastoreBundle\Entity\ORM\Game;

class GameController extends Controller
{
	/**
	 * TODO: implement a simple HTML interface for interacting with the API?
	 * 
	 * @return Response
	 */
    // public function indexAction() {}

    /**
     * [createAction description]
     * 
     * @return JsonResponse
     */
    public function createAction()
    {
    	$game = new Game;

    	$game->setWord($this->getWordRepository()->getRandomWord());

    	$this->saveGame($game);

    	return $this->genGameResponse($game);
    }

    /**
     * [statusAction description]
     * 
     * @param  integer 		$game_id 
     * @return JsonResponse
     */
    public function statusAction($game_id)
    {
    	return $this->genGameResponse($this->getGame($game_id));
    }

    /**
     * [guessAction description]
     * 
     * @param  integer 		$game_id 
     * @param  string 		$letter
     * @return JsonResponse
     */
    public function guessAction($game_id, $letter)
    {
    	$game = $this->getGame($game_id, true);

    	if ($game->addCharacterGuessed($letter))
    		$this->saveGame($game);

    	return $this->genGameResponse($game);
    }

    /**************************************************************************/

    /**
     * helper method for generating api Responses
     * 
     * @param  Game   		$game 
     * @return JsonResponse
     */
    protected function genGameResponse(Game $game)
    {
    	/*
    	// the following is best practice for SF2.6+ (but does not work in prior versions)
    	$o = new \ArrayObject();

		$o->game_id    = $game->getId();
    	$o->word 	   = $game->getGuessedWord();
    	$o->tries_left = $game->getTriesLeft();
    	$o->status 	   = $game->getStatus();
		*/
	
		$o = array(
			'game_id'	 => $game->getId(),
	    	'word' 	     => $game->getGuessedWord(),
	    	'tries_left' => $game->getTriesLeft(),
	    	'status' 	 => $game->getStatus(),
		);

    	return new JsonResponse($o);
    }

    /**
     * retrieve a Game entity 
     * 
     * @param  integer  $id             
     * @param  boolean 	$req_unfinished 	- FALSE by default, whether to only return a game if it is still in progress
     * @return Game
     */
    protected function getGame($id, $req_unfinished = false)
    {
	    // retrieve the object from database
	    $game = $this->getGameRepository()->find($id);

	    if (!$game)
	        throw $this->createNotFoundException("Game #{$id} does not exist.");

	    if ($req_unfinished && $game->getStatus() !== Game::STATUS_BUSY)
	    	throw new BadRequestHttpException("Game #{$id} has already finished.");

	    return $game;
    }

    /**
     * save a Game entity. (both inserts & updates)
     * 
     * @param  Game   $game [description]
     * @return void
     */
    protected function saveGame(Game $game)
    {   
    	$this->getGameRepository()->save($game);    	
    }

    /**
     * retrieve an EntityRepository for Word entities
     * 
     * @return Hangman\Bundle\DatastoreBundle\Repository\ORM\WordRepository
     */
    protected function getWordRepository()
    {
    	return $this->get('hangman_datastore.word_repository');
    }

    /**
     * retrieve an EntityRepository for Game entities
     * 
     * @return Hangman\Bundle\DatastoreBundle\Repository\ORM\GameRepository
     */
    protected function getGameRepository()
    {
    	return $this->get('hangman_datastore.game_repository');
    }
} 