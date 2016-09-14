<?php
session_start();

//namespace NScontrollers;
 
    $_SESSION['host'] = $_POST['host'];
    $_SESSION['username'] = $_POST['username'];
    $_SESSION['password'] = $_POST['password'];
 
try
{
   $db = new PDO("mysql:host=".$_SESSION['host'], $_SESSION['username'], $_SESSION['password'],array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
}
catch (Exception $e)
{
    echo ('err101');
    return;
}

$database_rows=[];

foreach ($db_fetch = $db->query('SHOW DATABASES')->fetchAll(PDO::FETCH_ASSOC) as $values)
{
   array_push($database_rows, $values['Database']);
}

$_SESSION['database_rows']=$database_rows;

$db = null;  

?>