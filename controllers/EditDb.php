<?php

session_start();

try
{
    $db = new PDO("mysql:host=".$_SESSION['host'], $_SESSION['username'], $_SESSION['password'],array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql="CREATE DATABASE ".$_POST['database_name'];
    $db->exec($sql);
    echo "ok";
    array_push($_SESSION['database_rows'],$_POST['database_name']);
}
catch (Exception $e)
{
    echo ('err101');
    return;
}

?>