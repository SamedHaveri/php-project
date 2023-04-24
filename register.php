<?php
include("./DatabaseConnector.php");
include("./UserGateway.php");

function getDb(){
    $dbConnection = (new DatabaseConnector())->getConnection();
    return $dbConnection;
}

try{
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    if(!isset($_POST["username"]) || !isset($_POST["password"]) ){
        http_response_code(400);
        exit("Bad Request");
    }
    $db = getDb();
    if($db == null){
        http_response_code(502);
        exit("DB Connection Issue");
    }
    $userGateway = new UserGateway($db);

    //validate unique username
    $existingUser = $userGateway->findByUsername($username);
    if($existingUser != null){
        http_response_code(400);
        exit("Username already exists");
    }

    //validate passoword
    if(strlen($password) < 8){
        http_response_code(400);
        exit("Password too short. Minimum 8 characters");
    }

    $user = array("username" => $username, "password"=> $password);
    $userGateway->insert($user);


}catch(Exception $e){
    exit($ex->getMessage());
}