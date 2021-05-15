<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
class Hash_url{

	protected $pdo;
	protected static $table = "hashed_urls";

	public function __construct(PDO $pdo)
	{
		$this->pdo = $pdo;
	}

	private function validation($url){
		if(empty($url)) return http_response_code(422);
		if(!filter_var($url,FILTER_VALIDATE_URL)) return http_response_code(422);
	}
	protected function base62encode($data) {
		$outstring = '';
		$l = strlen($data);
		for ($i = 0; $i < $l; $i += 8) {
			$chunk = substr($data, $i, 8);
			$outlen = ceil((strlen($chunk) * 8)/6); //8bit/char in, 6bits/char out, round up
			$x = bin2hex($chunk);  //gmp won't convert from binary, so go via hex
			$w = gmp_strval(gmp_init(ltrim($x, '0'), 16), 62); //gmp doesn't like leading 0s
			$pad = str_pad($w, $outlen, '0', STR_PAD_LEFT);
			$outstring .= $pad;
		}
		return $outstring;
	}

	protected function short_string($str){
		$md5_hash = md5($str);
		$_base64 = $this->base62encode(substr($md5_hash,0,7));
		return $_base64;
	}

	protected function prepare_final_url($url){
		if(isset($_SERVER['HTTPS'])){
			$protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
		}
		else{
			$protocol = 'http';
		}
		return $protocol . "://" . $_SERVER['HTTP_HOST'] . "/" . $url;
	}
	public function set_hash_url($url){
		if($this->validation($url)) return;
		$hashed_url = $this->short_string($url);

		try {
			$query = "INSERT INTO " . self::$table .
						" (original_url,hashed_url) " .
						" VALUES (:long_url,:hashed_url)";
			$stmt = $this->pdo->prepare($query);
			$params = array(
				"long_url" => $url,
				"hashed_url" => $hashed_url
			);
			$stmt->execute($params);
			$prepared_url = $this->prepare_final_url($hashed_url);
			echo json_encode(["status" => 200, "data" => $prepared_url]);
		} catch(PDOException $e) {
			return json_encode(["status" => 502, "data" => "Already existing url"]);
		}
	}

}

