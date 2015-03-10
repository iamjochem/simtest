# SIM Test

A simple test application implementing a minimal REST-like API for playing a game of hangman.

> it was suggested that guessing a letter should be performed with an HTTP PUT request, I disagree with this because [RFC2616](http://www.ietf.org/rfc/rfc2616) states PUT requests should be idempotent and I feel that repeatedly guessing the same [incorrect] letter should cause the "tries left" to be repeated decremented (this is how I remember playing the game at school, it also make the game a little harder and, IMHO, a little more fun), Additionally PUT request URIs are supposed to enclose (in the request body) a modified version of the existing resource (i.e. the game), I do not feel that this is the correct semantics to use in this case because guessing a letter is not a direct modification of the resource - adding (i.e. POST) a guess may have as a side-effect that the game in question is modified. Therefore I chose to implement the "guess endpoint"



## API endpoints

```
1. [POST] /games 				- create a new game
2. [GET]  /games/:id 			- retrieve existing game data
3. [POST] /games/:id/:char
```

All endpoints return the same JSON game-data structure (assuming no errors were encountered), in the event of some kind of error (regardless of type) symfony-standard error output will be generated as JSON ... this is not particularly helpful, in a real-world application API error output should be normalized/improved such that the base error output is the same regardless of the whether the app is in "debug" mode:



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
	"status"	: <STATUS>,
	"word"		: <WORD_STRING>
}
```

with the following logical replacements:

1. `<INTEGER>`		- an unsigned integer
2. `<STATUS>`		- one of the following string values: busy, fail, success
3. `<WORD_STRING>`	- a string representing the guessed word, unguessed letters are replaced by the `.` (period) character.

an example with real values:

```json
{
	"game_id" 	: 1,
	"tries_left": 11,
	"status"	: "busy",
	"word"		: "...."
}
```

### Error responses

As mentioned above, error response data structures are currently determined based on "debug" configuration.

#### With "debug" (e.g. in the "dev" ENV)

```json
[
    {
        "message": "Game #1 has already finished.",
        "class"  : "Symfony\\Component\\HttpKernel\\Exception\\BadRequestHttpException",
        "trace"  : [/* lots of object literals here! */]
    }
]
```

### Without "debug" (e.g. in the "prod" ENV)

```json
{
    "error"      : {
        "code"   : 400,
        "message": "Bad Request"
    }
}
```

Note that in both the above cases the HTTP response status code is *`400: Bad Request`*, ideally the custom error message should always be returned and debug information (i.e. `class`, `trace` properties) should be included additively.



## Installing the app

The following assumptions are made regarding the system where you are installing this app:

1. You have `git` installed. 

2. You have `php` installed (version 5.3.2+).

3. You have a working [global] copy of the `composer` command installed, if not please follow the instructions found [here](https://getcomposer.org/doc/00-intro.md), an alternative for MacOSX users is to run `brew install composer`.

Run the following commands in a directory of your choice (you will be asked for a few configuration details during the process):

```
git clone git@github.com:iamjochem/simtest.git
cd simtest
composer install
app/console doctrine:database:create
app/console doctrine:migrations:migrate
app/console doctrine:fixtures:load
```

## Testing the app

From the root of your project directory run the following:

```sh
./bin/phpunit -c app
```


### Running the app

For development purposes you can run the application using symfony's built-in web-server using the following command (from the root of your project):

```sh
app/console server:run
```

For production enviroments you will need to setup a web-server with a suitable configuration, I recommend using [Nginx](http://nginx.org/).

Below is an example vhost configuration for Nginx:

```
server {
    listen       80;
    server_name  siminterview.local;
    root         /work/test/siminterview/web;

    location / {
        try_files $uri /app.php$is_args$args;
    }

    location ~ ^/(app_dev|config)\.php(/|$) {
        include         /usr/local/etc/nginx/conf.d/php-fpm.settings;         
        fastcgi_param   HTTPS off;
    }

    # PROD
    location ~ ^/app\.php(/|$) {
        include         /usr/local/etc/nginx/conf.d/php-fpm.settings;         
        fastcgi_param   HTTPS off;

        # Prevents URIs that include the front controller. This will 404:
        # http://domain.tld/app.php/some-path
        # Remove the internal directive to allow URIs like this
        internal;
    }
}
```
