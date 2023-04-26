<?php
include("./DatabaseConnector.php");
include("./UserGateway.php");

function getDb(){
    $dbConnection = (new DatabaseConnector())->getConnection();
    return $dbConnection;
}
session_start();

try{
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
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

    //validate unique username
    $existingUser = $userGateway->findByUsername($username);
    if($existingUser != null){
        http_response_code(400);
        throw new Exception("Username already exists");
    }

    //validate passoword
    if(strlen($password) < 8){
        http_response_code(400);
        throw new Exception("Password too short. Minimum 8 characters");
    }

    $user = array("username" => $username, "password"=> $password);
    $userGateway->insert($user);
    $_SESSION['token']=$username;
    redirect("/index.php");

}catch(Exception $e){
    $_SESSION['login_error']=$e->getMessage();
    redirect("/Login.php");
    exit($e->getMessage());
}

function redirect($url, $statusCode = 303)
{
   header('Location: ' . $url, true, $statusCode);
   die();
}