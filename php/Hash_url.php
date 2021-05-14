<?php


class Hash_url{

	protected $pdo;
	protected static $table = "hashed_urls";

	public function __construct(PDO $pdo)
	{
		$this->pdo = $pdo;

		//validations
		if(empty($url)) return http_response_code(422);
		if(!filter_var($url,FILTER_VALIDATE_URL)) return http_response_code(422);
	}

	protected function hash_url($url){
		//...code
	}


}

