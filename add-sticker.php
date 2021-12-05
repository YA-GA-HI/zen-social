<?php
session_start();
//if there a post request 

if (isset($_GET['author']) && isset($_GET['sticker']) ) 
{   
    include_once('src/dbconnect.php'); 
    
    //load stickers
    $stmt = $conn->prepare(" INSERT INTO favorite_stickers(username,sticker,author) VALUES(:username,:sticker,:author) ");
    $stmt->bindParam('username',$_SESSION['user']['username']);
    $stmt->bindParam('author',$_GET['author']);
    $stmt->bindParam('sticker',$_GET['sticker']);
    $stmt->execute();
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    die();

}