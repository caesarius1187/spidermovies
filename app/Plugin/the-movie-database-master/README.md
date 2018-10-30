# TheMovieDatabase plugin for CakePHP

## Installation

You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

The recommended way to install composer packages is:

```
composer require cakephp-fr/the-movie-database
```

Before you can do any request you need set information :

```php
// in config/app.php
    'Datasources' => [
        // other datasources
        'theMovieDatabase' => [
            'className' => 'TheMovieDatabase\Datasource\Connection',
            'driver' => 'TheMovieDatabase\Datasource\Connection',
            'apiKey' => '************',
            'lang' => 'fr'
        ],
    ]
```

## Usage
```php
// in your controller
use Cake\Datasource\ConnectionManager;

class MyController extends AppController {

	public function index() {

		$conn = ConnectionManager::get('theMovieDatabase'); // get the connection

        $tvShow = $conn->searchTv('supernatural'); // execute a query

        debug($tvShow); // watch the result

	}

}
```

Watch source Datasource/Connection.php for other query


## Resource

You can get your api key [here](https://www.themoviedb.org/documentation/api).

For more information [here](http://docs.themoviedb.apiary.io/).
