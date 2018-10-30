<?php 

namespace TheMovieDatabase\Datasource;

use Cake\Network\Http\Client;
/**
* 
*/
class Connection
{

	protected $_config = [];

	protected $_apiKey = '';

	protected $_http = null;

	protected $_lang = 'en';

	protected $_url = 'http://api.themoviedb.org/3/';

	public function __construct(array $config = [])	{

		if(!array_key_exists('apiKey', $config) || empty($config['apiKey'])) {
			throw new InvalidArgumentException("ApiKey is not found or empty");
		}

		if(array_key_exists('apiKey', $config) && !empty($config['apiKey'])) {
			$this->_lang = $config['lang'];
		}

		$this->_apiKey = $config['apiKey'];

		$this->_config = $config;

		$this->_http = new Client();
	}

	public function getConfig()	{
		return $this->_config;
	}

	public function findId($externalSource, $id)
	{
		$params = [
			'type' => 'find/' . $id,
			'external_source' => $externalSource
		];

		return $this->_query($params);
	}

	public function genreMovieList()
	{
		$params = [
			'type' => 'genre/movie/list'
		];

		return $this->_query($params);
	}

	public function genreTvList()
	{
		$params = [
			'type' => 'genre/tv/list'
		];

		return $this->_query($params);
	}

	public function genreIdMovies($id)
	{
		$params = [
			'type' => 'genre/' . $id . '/movies'
		];

		return $this->_query($params);
	}

	public function movieId($id)
	{
		$params = [
			'type' => 'movie/' . $id
		];

		return $this->_query($params);
	}

	public function movieIdImages($id)
	{
		$params = [
			'type' => 'movie/' . $id . '/images'
		];

		return $this->_query($params);
	}

	public function movieIdVideos($id)
	{
		$params = [
			'type' => 'movie/' . $id . '/videos'
		];

		return $this->_query($params);
	}

	public function personId($id)
	{
		$params = [
			'type' => 'person/' . $id
		];

		return $this->_query($params);
	}

	public function personIdImages($id)
	{
		$params = [
			'type' => 'person/' . $id . '/images'
		];

		return $this->_query($params);
	}

	public function searchMovie($query)	{

		$params = [
			'type' => 'search/movie',
			'query' => $query
		];

		return $this->_query($params);
	}

	public function searchPerson($query)
	{
		$params = [
			'type' => 'search/person',
			'query' => $query
		];

		return $this->_query($params);
	}

	public function searchTv($query)
	{
		$params = [
			'type' => 'search/tv',
			'query' => $query
		];

		return $this->_query($params);
	}

	public function tvId($id)
	{
		$params = [
			'type' => 'tv/' . $id
		];

		return $this->_query($params);
	}

	public function tvIdImages($id)
	{
		$params = [
			'type' => 'tv/' . $id . '/images'
		];

		return $this->_query($params);
	}

	public function tvIdVideos($id)
	{
		$params = [
			'type' => 'tv/' . $id . '/videos'
		];

		return $this->_query($params);
	}

	public function tvIdSeasonNumber($id, $seasonNumber)
	{
		$params = [
			'type' => 'tv/' . $id . '/season/' . $seasonNumber
		];

		return $this->_query($params);
	}

	public function tvIdSeasonNumberImages($id, $seasonNumber)
	{
		$params = [
			'type' => 'tv/' . $id . '/season/' . $seasonNumber . '/images'
		];

		return $this->_query($params);
	}

	public function tvIdSeasonNumberVideos($id, $seasonNumber)
	{
		$params = [
			'type' => 'tv/' . $id . '/season/' . $seasonNumber . '/videos'
		];

		return $this->_query($params);
	}

	public function tvIdSeasonNumberEpisodeNumber($id, $seasonNumber, $episodeNumber)
	{
		$params = [
			'type' => 'tv/' . $id . '/season/' . $seasonNumber . '/episode/' . $episodeNumber
		];

		return $this->_query($params);
	}

	public function tvIdSeasonNumberEpisodeNumberImages($id, $seasonNumber, $episodeNumber)
	{
		$params = [
			'type' => 'tv/' . $id . '/season/' . $seasonNumber . '/episode/' . $episodeNumber . '/images'
		];

		return $this->_query($params);
	}

	public function tvIdSeasonNumberEpisodeNumberVideos($id, $seasonNumber, $episodeNumber)
	{
		$params = [
			'type' => 'tv/' . $id . '/season/' . $seasonNumber . '/episode/' . $episodeNumber . '/videos'
		];

		return $this->_query($params);
	}

	protected function _query($params)
	{

		$url = $this->_url . $params['type'];
		unset($params['type']);
		$params = array_merge($params, ['language' => $this->_lang, 'api_key' => $this->_apiKey]);
		
		$url .= '?' . http_build_query($params);
		
		return $this->_http->get($url);
	}
}