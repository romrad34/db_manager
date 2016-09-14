<?php

session_start();

try
{
    $db = new PDO("mysql:host=".$_SESSION['host'], $_SESSION['username'], $_SESSION['password'],array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql="DROP DATABASE ".$_POST['database_name'];
    $db->exec($sql);
    echo "ok";
    foreach ($_SESSION['database_rows'] as $key => $value)
    {
            if ($value===$_POST['database_name'] )
            {

            unset($_SESSION['database_rows'][$key]);
            }
    }
}
catch (Exception $e)
{
    echo ('err101');
    return;
}

?>