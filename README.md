# SIM Test

A simple test application implementing a minimal REST-like api for playing a game of hangman.

> it was suggested that guessing a letter should be performed with an HTTP PUT request, I disagree with this because [RFC2616](http://www.ietf.org/rfc/rfc2616) states PUT requests should be idempotent and I feel that repeatedly guessing the same [incorrect] letter should cause the "tries left" to be repeated decremented (this is how I remember playing the game at school, it also make the game a little harder and, IMHO, a little more fun), Additionally PUT request URIs are supposed to enclose (in the request body) a modified version of the exiting resource (i.e. the game), I do not feel that this is the correct semantics to use in this case because guessing a letter is not a direct modification of the resource - adding (i.e. POST) a guess may have as a side-effect that the game in question is modified. Therefore I chose to implement the "guess endpoint"



## API endpoints

```
1. [POST] /games 				- create a new game
2. [GET]  /games/:id 			- retrieve existing game data
3. [POST] /games/:id/:char
```

all endpoints return the same JSON game-data structure (assuming no errors were encountered), in the event of some kind of error (regardless of type) symfony-standard error output will be generated as JSON ... this is not particularly helpful, in a real-world application API error output should be normalized/improved.

### API parameters

#### :id

an integer value (i.e. the "game_id" value of an API JSON response)

#### :char

a single, lowercase letter between `a` and `z`


## API response data

all endpoints return the following JSON data structure (assuming no errors);

```json
{
	"game_id" 	: <INTEGER>,
	"tries_left": <INTEGER>,
	"status"	: "<STATUS>",
	"word"		: "<WORD_STRING>"
}
```

with the following logical replacements:

1. <INTEGER>		- an unsigned integer
2. <STATUS>			- one of the following string values: busy, fail, success
3. <WORD_STRING>	- a string representing the guessed word, unguessed letters are replaced by a `.`



## App Installation




## App Testing

From the root of your project directory run the following:

```sh
./bin/phpunit -c app
```