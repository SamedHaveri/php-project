<?php
declare(strict_types=1);
include("./DatabaseConnector.php");
include("./UserGateway.php");
session_start();

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

    $_SESSION['token']=$username;
    redirect("/index.php");
}catch(Exception $ex){
    $_SESSION['login_error']=$ex->getMessage();
    redirect("/Login.php");
    exit($ex->getMessage());
}

function redirect($url, $statusCode = 303)
{
   header('Location: ' . $url, true, $statusCode);
   die();
}