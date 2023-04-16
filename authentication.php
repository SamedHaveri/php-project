<?php
declare(strict_types=1);
use Firebase\JWT\JWT;
require_once('../vendor/autoload.php');
include("./jwtUtil.php");
include("./DatabaseConnector.php");
include("./UserGateway.php");


function getDb(){
    $dbConnection = (new DatabaseConnector())->getConnection();
    return $dbConnection;
}

try{
    $username = $_POST["username"];
    $password = $_POST["password"];
    if(!isset($_POST["username"]) || !isset($_POST["password"]) ){
        http_response_code(400);
        throw new Exception("Bad Request");
    }
    $db = getDb();
    if($db == null){
        http_response_code(502);
        throw new Exception("DB Connection Issue");
    }
    $userGateway = new UserGateway($db);

    //authenticate in db with pdo give response to user if unauthenticated else return token
    $loggingUser = $userGateway->findByUsernamePassword($username, $password);
    if($loggingUser == null){
        http_response_code(403);
        throw new Exception("Unauthorized");
    }

    $issuedAt   = new DateTimeImmutable();
    $expire     = $issuedAt->modify($expiry)->getTimestamp();      // Add 60 seconds
    $username   = $username;                                      // Retrieved from filtered POST data

    $data = [
        'iat'  => $issuedAt->getTimestamp(),         // Issued at: time when the token was generated
        'iss'  => $serverName,                       // Issuer
        'nbf'  => $issuedAt->getTimestamp(),         // Not before
        'exp'  => $expire,                           // Expire
        'userName' => $username,                     // User name
    ];

    echo JWT::encode(
        $data,
        $secretKey,
        $alg
    );
}catch(Exception $ex){
    exit($ex->getMessage());
}