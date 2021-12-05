<?php
session_start();
if(isset($_SESSION['user']))
{
    unset($_SESSION['user']);
    $_SESSION['msg'] = "You Logged Out!";
    $_SESSION['color'] = "red";
}

header('location: login.php');
