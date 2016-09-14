<?php
session_start();
$db = null;

if (isset($_POST['dbname']))
{
    try
    {
       $db = new PDO("mysql:host=".$_SESSION['host'].";dbname=".$_POST['dbname'], $_SESSION['username'], $_SESSION['password'],array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    }
    catch (Exception $e)
    {
        echo ('err101');
        return;
    }

    $db->exec('DROP TABLE '.$_POST['tablename']);

}
?>