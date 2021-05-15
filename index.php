<?php
include "./php/config.php";
include "./php/Hash_url.php";
header('Content-Type: application/json');

try {
    $pdo = new PDO("mysql:host=" . DB_HOST .";dbname=" . DB_DATABASE, DB_USERNAME, DB_PASSWORD);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e) {
    return json_encode(["status" => 503, "data" => "database connection failed, pls check conf."]);
    exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$json = file_get_contents('php://input');
	$obj = json_decode($json);
	if(!isset($obj->url)) {
		echo json_encode(["status" => 400, "data" => "url field can't be empty"]);
		return;
	}
	$hash_url = new Hash_url($pdo);
	$res = $hash_url->set_hash_url($obj->url);
	echo $res;
}

if($_SERVER['REQUEST_METHOD'] == 'GET'){
	$json = file_get_contents('php://input');
	$obj = json_decode($json);
	if(!isset($obj->url)) {
		echo json_encode(["status" => 400, "data" => "url field can't be empty"]);
		return;
	}
	$hashed_url = $obj->url;
	$hashed_url = parse_url($hashed_url, PHP_URL_PATH);
	$hashed_url = substr($hashed_url,1);
	$query = "SELECT original_url from hashed_urls where hashed_url = :hashed_url" 	;
		$stmt = $pdo->prepare($query);
		$params = array(
			"hashed_url" => $hashed_url
		);
		$stmt->execute($params);	
		$row = $stmt->fetch();
		if(!$row){
		echo json_encode(["status" => 404, "data" => "url not found associated with this hashed url"]);
		return;
		}
		echo json_encode(["status" => 200, "data" => $row['original_url']]);
}




